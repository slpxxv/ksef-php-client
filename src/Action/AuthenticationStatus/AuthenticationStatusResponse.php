<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticationStatus;

final readonly class AuthenticationStatusResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}

    public function code(): int
    {
        return (int) ($this->data['status']['code'] ?? 0);
    }
}
