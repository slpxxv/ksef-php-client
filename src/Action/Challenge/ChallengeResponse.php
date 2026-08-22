<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\Challenge;

final readonly class ChallengeResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}

    /** @return array{challenge: string, timestampMs: int, timestamp: string, clientIp?: string} */
    public function typedData(): array
    {
        $challenge = $this->data['challenge'] ?? null;
        $timestampMs = $this->data['timestampMs'] ?? null;
        $timestamp = $this->data['timestamp'] ?? null;
        $clientIp = $this->data['clientIp'] ?? null;
        if (!is_string($challenge) || !is_int($timestampMs) || !is_string($timestamp)) {
            throw new \InvalidArgumentException('Invalid KSeF challenge payload.');
        }
        if ($clientIp !== null && !is_string($clientIp)) {
            throw new \InvalidArgumentException('Invalid KSeF challenge client IP.');
        }

        $result = [
            'challenge' => $challenge,
            'timestampMs' => $timestampMs,
            'timestamp' => $timestamp,
        ];
        if ($clientIp !== null) {
            $result['clientIp'] = $clientIp;
        }

        return $result;
    }
}
