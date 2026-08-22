<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RefreshAccessToken;

use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Model\AuthTokens;

final class RefreshAccessTokenAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(RefreshAccessTokenRequest $request): RefreshAccessTokenResponse
    {
        $tokens = AuthTokens::fromArray($this->api->requestJson(
            'POST',
            '/auth/token/refresh',
            null,
            $request->refreshToken,
        ));
        $this->api->setAccessToken($tokens->accessToken->token);

        return new RefreshAccessTokenResponse($tokens);
    }
}
