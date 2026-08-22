<?php

declare(strict_types=1);

namespace slpxxv\ksef\Model;

final readonly class AuthTokens
{
    public function __construct(
        public TokenInfo $accessToken,
        public TokenInfo $refreshToken,
    ) {}

    /** @param array<int|string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $accessToken = $data['accessToken'] ?? null;
        $refreshToken = $data['refreshToken'] ?? null;
        if (!is_array($accessToken) || !is_array($refreshToken)) {
            throw new \InvalidArgumentException('Invalid KSeF authentication tokens payload.');
        }

        return new self(TokenInfo::fromArray($accessToken), TokenInfo::fromArray($refreshToken));
    }

}
