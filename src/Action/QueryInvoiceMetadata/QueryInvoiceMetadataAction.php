<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryInvoiceMetadata;

use slpxxv\ksef\Http\ApiClient;

final class QueryInvoiceMetadataAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(QueryInvoiceMetadataRequest $request): QueryInvoiceMetadataResponse
    {
        return new QueryInvoiceMetadataResponse($this->api->requestJson('POST', '/invoices/query/metadata', $request->filters));
    }
}
