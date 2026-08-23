<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionStatus;

use slpxxv\ksef\Http\ApiClient;

final class SessionStatusAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(SessionStatusRequest $request): SessionStatusResponse
    {
        return new SessionStatusResponse($this->api->requestJson('GET', '/sessions/' . rawurlencode($request->sessionReference)));
    }
}
