<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GenerateToken;

use slpxxv\ksef\Http\ApiClient;

final class GenerateTokenAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(GenerateTokenRequest $request): GenerateTokenResponse
    {
        return new GenerateTokenResponse($this->api->requestJson('POST', '/tokens', $request->request));
    }
}
