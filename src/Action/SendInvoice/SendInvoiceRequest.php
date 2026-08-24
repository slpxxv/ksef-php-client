<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SendInvoice;

use slpxxv\ksef\Model\EncryptionData;
use slpxxv\ksef\Model\OnlineSession;

final readonly class SendInvoiceRequest
{
    public function __construct(
        public string|OnlineSession $session,
        public string               $invoiceXml,
        public EncryptionData       $encryption,
    ) {}
}
