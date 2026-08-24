<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionInvoices;

final readonly class SessionInvoicesRequest
{
    public function __construct(
        public string  $sessionReference,
        public ?string $continuationToken = null,
        public ?int    $pageSize = null,
    ) {}
}
