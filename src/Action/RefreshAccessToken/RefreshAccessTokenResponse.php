<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RefreshAccessToken;

use slpxxv\ksef\Model\AuthTokens;

final readonly class RefreshAccessTokenResponse
{
    public function __construct(public AuthTokens $tokens) {}
}
