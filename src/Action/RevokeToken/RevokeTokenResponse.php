<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RevokeToken;

final readonly class RevokeTokenResponse
{
    public function __construct(public bool $revoked = true) {}
}
