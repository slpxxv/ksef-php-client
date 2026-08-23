<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\OpenOnlineSession;

use slpxxv\ksef\Model\EncryptionData;

final readonly class OpenOnlineSessionRequest
{
    public function __construct(
        public EncryptionData $encryption,
        public string         $systemCode = 'FA (3)',
        public string         $schemaVersion = '1-0E',
        public string         $value = 'FA',
    ) {}
}
