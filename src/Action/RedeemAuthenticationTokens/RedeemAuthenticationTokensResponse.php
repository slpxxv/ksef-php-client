<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RedeemAuthenticationTokens;

use slpxxv\ksef\Model\AuthTokens;

final readonly class RedeemAuthenticationTokensResponse
{
    public function __construct(public AuthTokens $tokens) {}
}
