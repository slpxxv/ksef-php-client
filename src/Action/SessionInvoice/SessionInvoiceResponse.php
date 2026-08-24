<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoice;

final readonly class SessionInvoiceResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
