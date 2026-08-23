<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\CloseOnlineSession;

use slpxxv\ksef\Model\OnlineSession;

final readonly class CloseOnlineSessionRequest
{
    public function __construct(public string|OnlineSession $session) {}
}
