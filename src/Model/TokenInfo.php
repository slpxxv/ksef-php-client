<?php

declare(strict_types=1);

namespace slpxxv\ksef\Model;

final readonly class TokenInfo
{
    public function __construct(
        public string $token,
        public string $validUntil,
    ) {}

    /** @param array<int|string, mixed> $data */
    public static function fromArray(array $data): self
    {
        if (!is_string($data['token'] ?? null) || !is_string($data['validUntil'] ?? null)) {
            throw new \InvalidArgumentException('Invalid KSeF token payload.');
        }

        return new self($data['token'], $data['validUntil']);
    }
}
