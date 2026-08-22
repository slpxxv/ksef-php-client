<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\PublicKeyCertificate;

final readonly class PublicKeyCertificateRequest
{
    public function __construct(public string $usage) {}
}
