<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\StartKsefTokenAuthentication;

final readonly class StartKsefTokenAuthenticationRequest
{
    public function __construct(
        public string $token,
        public string $contextType,
        public string $contextValue,
        public string $publicKeyPem,
        public string $publicKeyId,
    ) {}
}
