<?php

declare(strict_types=1);

namespace slpxxv\ksef\Exception;

use Throwable;

final class ApiException extends KsefException
{
    public function __construct(
        string                 $message,
        private readonly int   $statusCode,
        /** @var array<string, string> */
        private readonly array $responseHeaders = [],
        private readonly mixed $payload = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /** @return array<string, string> */
    public function responseHeaders(): array
    {
        return $this->responseHeaders;
    }

    public function payload(): mixed
    {
        return $this->payload;
    }
}
