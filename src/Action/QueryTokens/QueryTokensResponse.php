<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\QueryTokens;

final readonly class QueryTokensResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
