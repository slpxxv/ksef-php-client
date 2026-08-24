<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoice;

use slpxxv\ksef\Http\ApiClient;

final class SessionInvoiceAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(SessionInvoiceRequest $request): SessionInvoiceResponse
    {
        return new SessionInvoiceResponse($this->api->requestJson(
            'GET',
            '/sessions/' . rawurlencode($request->sessionReference)
            . '/invoices/' . rawurlencode($request->invoiceReferenceNumber),
        ));
    }
}
