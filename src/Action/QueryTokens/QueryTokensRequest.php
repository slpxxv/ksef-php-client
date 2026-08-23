<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryTokens;

final readonly class QueryTokensRequest
{
    /** @param array<string, mixed> $query */
    public function __construct(public array $query = []) {}
}
