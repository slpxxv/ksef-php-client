<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RedeemAuthenticationTokens;

final readonly class RedeemAuthenticationTokensRequest
{
    public function __construct(public string $authenticationToken) {}
}
