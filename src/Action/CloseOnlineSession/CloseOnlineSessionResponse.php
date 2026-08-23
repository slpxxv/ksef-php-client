<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\CloseOnlineSession;

final readonly class CloseOnlineSessionResponse
{
    public function __construct(public bool $closed = true) {}
}
