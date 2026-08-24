<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoiceUpo;

final readonly class SessionInvoiceUpoRequest
{
    public function __construct(
        public string $sessionReference,
        public string $invoiceReferenceNumber,
    ) {}
}
