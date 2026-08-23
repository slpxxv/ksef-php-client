<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\CloseOnlineSession;

use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Model\OnlineSession;

final class CloseOnlineSessionAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(CloseOnlineSessionRequest $request): CloseOnlineSessionResponse
    {
        $reference = $request->session instanceof OnlineSession
            ? $request->session->referenceNumber
            : $request->session;
        $this->api->requestJson('POST', '/sessions/online/' . rawurlencode($reference) . '/close');

        return new CloseOnlineSessionResponse();
    }
}
