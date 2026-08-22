<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RefreshAccessToken;

final readonly class RefreshAccessTokenRequest
{
    public function __construct(public string $refreshToken) {}
}
