<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\PublicKeyCertificate;

final readonly class PublicKeyCertificateResponse
{
    /** @param array<string, mixed> $certificate */
    public function __construct(public array $certificate) {}
}
