<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticateWithKsefToken;

use slpxxv\ksef\Model\AuthTokens;

final readonly class AuthenticateWithKsefTokenResponse
{
    public function __construct(public AuthTokens $tokens) {}
}
