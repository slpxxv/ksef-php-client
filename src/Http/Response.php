<?php

declare(strict_types=1);

namespace slpxxv\ksef\Http;

final readonly class Response
{
    public function __construct(
        public int    $statusCode,
        /** @var array<string, string> */
        public array  $headers,
        public string $body,
    ) {}

    public function json(): mixed
    {
        return $this->body === '' ? null : json_decode($this->body, true, 512, JSON_THROW_ON_ERROR);
    }
}
