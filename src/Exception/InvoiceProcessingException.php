<?php

declare(strict_types=1);

namespace slpxxv\ksef\Exception;

final class InvoiceProcessingException extends KsefException
{
    /** @param array<string, mixed> $status */
    public function __construct(string $message, public readonly array $status)
    {
        parent::__construct($message);
    }
}
