<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticateWithKsefToken;

final readonly class AuthenticateWithKsefTokenRequest
{
    public function __construct(
        public string $token,
        public string $contextType,
        public string $contextValue,
        public string $publicKeyPem,
        public string $publicKeyId,
        public int    $timeoutSeconds = 60,
        public int    $pollIntervalMilliseconds = 500,
    ) {}
}
