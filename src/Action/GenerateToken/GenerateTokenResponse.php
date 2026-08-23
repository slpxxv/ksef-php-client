<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GenerateToken;

final readonly class GenerateTokenResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
