<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryInvoiceMetadata;

final readonly class QueryInvoiceMetadataResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
