<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\WaitForInvoice;

final readonly class WaitForInvoiceRequest
{
    public function __construct(
        public string $sessionReference,
        public string $invoiceReferenceNumber,
        public int $timeoutSeconds = 120,
        public int $pollIntervalMilliseconds = 1000,
    ) {}
}
