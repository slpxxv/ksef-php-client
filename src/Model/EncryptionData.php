<?php

declare(strict_types=1);

namespace slpxxv\ksef\Model;

final readonly class EncryptionData
{
    public function __construct(
        public string $key,
        public string $iv,
        public string $encryptedSymmetricKey,
        public string $initializationVector,
        public string $publicKeyId,
    ) {}

    /** @param array{key: string, iv: string, encryptedSymmetricKey: string, initializationVector: string, publicKeyId: string} $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['key'],
            $data['iv'],
            $data['encryptedSymmetricKey'],
            $data['initializationVector'],
            $data['publicKeyId'],
        );
    }
}
