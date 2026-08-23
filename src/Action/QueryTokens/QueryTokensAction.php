<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryTokens;

use slpxxv\ksef\Http\ApiClient;

final class QueryTokensAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(QueryTokensRequest $request): QueryTokensResponse
    {
        return new QueryTokensResponse($this->api->requestJson('GET', '/tokens', null, null, $request->query));
    }
}
