<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoices;

use slpxxv\ksef\Http\ApiClient;

final class SessionInvoicesAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(SessionInvoicesRequest $request): SessionInvoicesResponse
    {
        $query = $request->pageSize === null ? [] : ['pageSize' => $request->pageSize];
        $headers = $request->continuationToken === null
            ? []
            : ['x-continuation-token' => $request->continuationToken];

        return new SessionInvoicesResponse($this->api->requestJson(
            'GET',
            '/sessions/' . rawurlencode($request->sessionReference) . '/invoices',
            null,
            null,
            $query,
            $headers,
        ));
    }
}
