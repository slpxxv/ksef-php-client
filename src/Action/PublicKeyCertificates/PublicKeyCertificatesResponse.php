<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\PublicKeyCertificates;

final readonly class PublicKeyCertificatesResponse
{
    /** @param list<array<string, mixed>> $certificates */
    public function __construct(public array $certificates) {}
}
