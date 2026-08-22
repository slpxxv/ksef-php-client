<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticationStatus;

use slpxxv\ksef\Http\ApiClient;

final class AuthenticationStatusAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(AuthenticationStatusRequest $request): AuthenticationStatusResponse
    {
        return new AuthenticationStatusResponse($this->api->requestJson(
            'GET',
            '/auth/' . rawurlencode($request->referenceNumber),
            null,
            $request->authenticationToken,
        ));
    }
}
