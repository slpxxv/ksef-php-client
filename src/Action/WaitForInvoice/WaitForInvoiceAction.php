<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\WaitForInvoice;

use slpxxv\ksef\Action\SessionInvoice\SessionInvoiceAction;
use slpxxv\ksef\Action\SessionInvoice\SessionInvoiceRequest;
use slpxxv\ksef\Exception\InvoiceProcessingException;
use slpxxv\ksef\Http\ApiClient;

final class WaitForInvoiceAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(WaitForInvoiceRequest $request): WaitForInvoiceResponse
    {
        if ($request->timeoutSeconds < 0) {
            throw new \InvalidArgumentException('Invoice processing timeout cannot be negative.');
        }
        if ($request->pollIntervalMilliseconds < 0) {
            throw new \InvalidArgumentException('Invoice polling interval cannot be negative.');
        }

        $invoiceStatusAction = new SessionInvoiceAction($this->api);
        $deadline = microtime(true) + $request->timeoutSeconds;

        do {
            $status = $invoiceStatusAction->execute(new SessionInvoiceRequest(
                sessionReference: $request->sessionReference,
                invoiceReferenceNumber: $request->invoiceReferenceNumber,
            ))->data;
            $code = (int) ($status['status']['code'] ?? 0);

            if ($code === 200) {
                return new WaitForInvoiceResponse($status);
            }

            if (!in_array($code, [100, 150], true)) {
                $description = (string) ($status['status']['description'] ?? 'Invoice processing failed.');
                $details = is_array($status['status']['details'] ?? null) ? $status['status']['details'] : [];
                $suffix = $details === [] ? '' : ' ' . implode(' ', array_map('strval', $details));

                throw new InvoiceProcessingException(trim($description . $suffix), $status);
            }

            if (microtime(true) < $deadline) {
                usleep($request->pollIntervalMilliseconds * 1000);
            }
        } while (microtime(true) < $deadline);

        throw new \RuntimeException(
            "Invoice '{$request->invoiceReferenceNumber}' was not processed within {$request->timeoutSeconds} seconds.",
        );
    }
}
