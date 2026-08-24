<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\Invoice;

final readonly class InvoiceResponse
{
    public function __construct(public string $content) {}
}
