<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\RevokeToken;

final readonly class RevokeTokenRequest
{
    public function __construct(public string $referenceNumber) {}
}
