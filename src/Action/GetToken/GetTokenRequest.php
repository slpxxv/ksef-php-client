<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\GetToken;

final readonly class GetTokenRequest
{
    public function __construct(public string $referenceNumber) {}
}
