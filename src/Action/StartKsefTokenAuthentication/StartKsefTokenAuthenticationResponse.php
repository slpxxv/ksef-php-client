<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\StartKsefTokenAuthentication;

final readonly class StartKsefTokenAuthenticationResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}

    /** @return array{referenceNumber: string, authenticationToken: array{token: string, validUntil: string}} */
    public function typedData(): array
    {
        $referenceNumber = $this->data['referenceNumber'] ?? null;
        $authenticationToken = $this->data['authenticationToken'] ?? null;
        if (!is_string($referenceNumber) || !is_array($authenticationToken)) {
            throw new \InvalidArgumentException('Invalid KSeF authentication initialization payload.');
        }

        $token = $authenticationToken['token'] ?? null;
        $validUntil = $authenticationToken['validUntil'] ?? null;
        if (!is_string($token) || !is_string($validUntil)) {
            throw new \InvalidArgumentException('Invalid KSeF authentication token payload.');
        }

        return [
            'referenceNumber' => $referenceNumber,
            'authenticationToken' => [
                'token' => $token,
                'validUntil' => $validUntil,
            ],
        ];
    }
}
