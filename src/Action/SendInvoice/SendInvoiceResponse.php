<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SendInvoice;

final readonly class SendInvoiceResponse
{
    /** @param array{referenceNumber: string} $data */
    public function __construct(public array $data) {}
}
