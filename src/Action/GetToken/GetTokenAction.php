<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GetToken;

use slpxxv\ksef\Http\ApiClient;

final class GetTokenAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(GetTokenRequest $request): GetTokenResponse
    {
        return new GetTokenResponse($this->api->requestJson('GET', '/tokens/' . rawurlencode($request->referenceNumber)));
    }
}
