<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\Challenge;

use slpxxv\ksef\Http\ApiClient;

final class ChallengeAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(ChallengeRequest $request): ChallengeResponse
    {
        return new ChallengeResponse($this->api->requestJson('POST', '/auth/challenge'));
    }
}
