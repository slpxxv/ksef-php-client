<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoices;

final readonly class SessionInvoicesResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
