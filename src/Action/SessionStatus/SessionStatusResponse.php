<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SessionStatus;

final readonly class SessionStatusResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data) {}
}
