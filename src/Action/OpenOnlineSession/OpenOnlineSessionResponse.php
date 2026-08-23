<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\OpenOnlineSession;

use slpxxv\ksef\Model\OnlineSession;

final readonly class OpenOnlineSessionResponse
{
    public function __construct(public OnlineSession $session) {}
}
