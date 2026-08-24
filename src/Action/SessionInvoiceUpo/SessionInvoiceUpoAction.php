<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoiceUpo;

use slpxxv\ksef\Http\ApiClient;

final class SessionInvoiceUpoAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(SessionInvoiceUpoRequest $request): SessionInvoiceUpoResponse
    {
        return new SessionInvoiceUpoResponse($this->api->requestBinary(
            'GET',
            '/sessions/' . rawurlencode($request->sessionReference)
            . '/invoices/' . rawurlencode($request->invoiceReferenceNumber) . '/upo',
        ));
    }
}
