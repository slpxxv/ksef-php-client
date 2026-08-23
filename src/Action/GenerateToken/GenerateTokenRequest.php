<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GenerateToken;

final readonly class GenerateTokenRequest
{
    /** @param array<string, mixed> $request */
    public function __construct(public array $request) {}
}
