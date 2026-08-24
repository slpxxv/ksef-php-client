<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoice;

final readonly class SessionInvoiceRequest
{
    public function __construct(
        public string $sessionReference,
        public string $invoiceReferenceNumber,
    ) {}
}
