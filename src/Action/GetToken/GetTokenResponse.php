<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GetToken;

final readonly class GetTokenResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
