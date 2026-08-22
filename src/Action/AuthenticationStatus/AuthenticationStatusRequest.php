<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticationStatus;

final readonly class AuthenticationStatusRequest
{
    public function __construct(
        public string $referenceNumber,
        public string $authenticationToken,
    ) {}
}
