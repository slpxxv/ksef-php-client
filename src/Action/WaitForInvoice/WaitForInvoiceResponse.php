<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\WaitForInvoice;

final readonly class WaitForInvoiceResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
