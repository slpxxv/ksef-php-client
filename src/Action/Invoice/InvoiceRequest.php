<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\Invoice;

final readonly class InvoiceRequest
{
    public function __construct(public string $ksefNumber) {}
}
