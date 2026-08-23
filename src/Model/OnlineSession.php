<?php

declare(strict_types=1);

namespace slpxxv\ksef\Model;

final readonly class OnlineSession
{
    public function __construct(
        public string $referenceNumber,
        public string $validUntil,
    ) {}

    /** @param array<int|string, mixed> $data */
    public static function fromArray(array $data): self
    {
        if (!is_string($data['referenceNumber'] ?? null) || !is_string($data['validUntil'] ?? null)) {
            throw new \InvalidArgumentException('Invalid KSeF online session payload.');
        }

        return new self($data['referenceNumber'], $data['validUntil']);
    }

}
