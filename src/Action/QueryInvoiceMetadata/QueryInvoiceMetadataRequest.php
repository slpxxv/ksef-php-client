<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryInvoiceMetadata;

final readonly class QueryInvoiceMetadataRequest
{
    /** @param array<string, mixed> $filters */
    public function __construct(public array $filters) {}
}
