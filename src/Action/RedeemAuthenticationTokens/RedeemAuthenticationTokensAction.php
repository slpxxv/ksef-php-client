<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RedeemAuthenticationTokens;

use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Model\AuthTokens;

final class RedeemAuthenticationTokensAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(RedeemAuthenticationTokensRequest $request): RedeemAuthenticationTokensResponse
    {
        return new RedeemAuthenticationTokensResponse(AuthTokens::fromArray($this->api->requestJson(
            'POST',
            '/auth/token/redeem',
            null,
            $request->authenticationToken,
        )));
    }
}
