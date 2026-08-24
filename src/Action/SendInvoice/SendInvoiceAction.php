<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\SendInvoice;

use slpxxv\ksef\Crypto\Cryptography;
use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Model\OnlineSession;

final class SendInvoiceAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(SendInvoiceRequest $request): SendInvoiceResponse
    {
        $sessionReference = $request->session instanceof OnlineSession
            ? $request->session->referenceNumber
            : $request->session;
        $encryptedInvoice = Cryptography::encryptAes256Cbc(
            $request->invoiceXml,
            $request->encryption->key,
            $request->encryption->iv,
        );

        $response = $this->api->requestJson(
            'POST',
            '/sessions/online/' . rawurlencode($sessionReference) . '/invoices',
            [
                'invoiceHash' => Cryptography::sha256Base64($request->invoiceXml),
                'invoiceSize' => strlen($request->invoiceXml),
                'encryptedInvoiceHash' => Cryptography::sha256Base64($encryptedInvoice),
                'encryptedInvoiceSize' => strlen($encryptedInvoice),
                'encryptedInvoiceContent' => base64_encode($encryptedInvoice),
                'offlineMode' => false,
            ],
        );
        $referenceNumber = $response['referenceNumber'] ?? null;
        if (!is_string($referenceNumber) || $referenceNumber === '') {
            throw new \RuntimeException('KSeF did not return an invoice reference number.');
        }

        return new SendInvoiceResponse(['referenceNumber' => $referenceNumber]);
    }
}
