<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionStatus;

final readonly class SessionStatusRequest
{
    public function __construct(public string $sessionReference) {}
}
