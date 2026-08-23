<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RevokeToken;

use slpxxv\ksef\Http\ApiClient;

final class RevokeTokenAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(RevokeTokenRequest $request): RevokeTokenResponse
    {
        $this->api->requestJson('DELETE', '/tokens/' . rawurlencode($request->referenceNumber));

        return new RevokeTokenResponse();
    }
}
