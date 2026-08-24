<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\Invoice;

use slpxxv\ksef\Http\ApiClient;

final class InvoiceAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(InvoiceRequest $request): InvoiceResponse
    {
        return new InvoiceResponse($this->api->requestBinary('GET', '/invoices/ksef/' . rawurlencode($request->ksefNumber)));
    }
}
