# KSeF PHP Client

[![CI](https://github.com/slpxxv/ksef-php-client/actions/workflows/ci.yml/badge.svg)](https://github.com/slpxxv/ksef-php-client/actions/workflows/ci.yml)

Typed PHP client for the Polish National e-Invoice System (KSeF) API 2.0.

The project provides a small, testable integration layer for authentication, public certificate discovery, encrypted online sessions, invoice submission, processing status polling and invoice retrieval.

## Highlights

- PHP 8.4+
- PSR-4 autoloading with the `slpxxv\ksef` namespace
- Action Pattern with dedicated Request and Response DTOs
- Dynamic action creation behind a single `KsefClient` entry point
- RSA-OAEP and AES-256-CBC encryption helpers
- Test, Demo and Production environments
- PHPStan level 8, PHP-CS-Fixer, PHPUnit and parallel-lint

## Installation

```bash
composer require slpxxv/ksef-php-client
```

## Quick start

```php
<?php

use slpxxv\ksef\Crypto\Cryptography;
use slpxxv\ksef\Environment;
use slpxxv\ksef\KsefClient;
use slpxxv\ksef\Model\EncryptionData;

require __DIR__ . '/vendor/autoload.php';

$client = KsefClient::forEnvironment(Environment::TEST);

// Certificates are selected by their intended KSeF usage.
$tokenCertificate = $client->publicKeyCertificate('KsefTokenEncryption');
$invoiceCertificate = $client->publicKeyCertificate('SymmetricKeyEncryption');

$ksefToken = getenv('KSEF_TOKEN');
if ($ksefToken === false || $ksefToken === '') {
    throw new RuntimeException('KSEF_TOKEN is not configured.');
}

$tokens = $client->authenticateWithKsefToken(
    token: $ksefToken,
    contextType: 'Nip',
    contextValue: '5265877635',
    publicKeyPem: $tokenCertificate['certificate'],
    publicKeyId: $tokenCertificate['publicKeyId'],
);

$client = $client->withAccessToken($tokens->accessToken->token);

$encryption = EncryptionData::fromArray(
    Cryptography::createEncryptionData(
        $invoiceCertificate['certificate'],
        $invoiceCertificate['publicKeyId'],
    ),
);

$session = $client->openOnlineSession($encryption);
$invoiceXml = file_get_contents(__DIR__ . '/invoice.xml');
if ($invoiceXml === false) {
    throw new RuntimeException('Invoice XML could not be read.');
}

$submitted = $client->sendInvoice($session, $invoiceXml, $encryption);
$client->closeOnlineSession($session);

// Wait until KSeF finishes processing the invoice.
$status = $client->waitForInvoice(
    $session->referenceNumber,
    $submitted['referenceNumber'],
);

echo $status['status']['description'] ?? 'Invoice processed.';

// Download the invoice by the KSeF number assigned during processing.
$downloadedXml = $client->invoice($status['ksefNumber']);
file_put_contents(__DIR__ . '/downloaded-invoice.xml', $downloadedXml);
```

## Supported features

| Area | Feature | `KsefClient` method |
|---|---|---|
| Certificates | Public key certificates | `publicKeyCertificates()`, `publicKeyCertificate()` |
| Authentication | Challenge | `challenge()` |
| Authentication | KSeF token authentication | `authenticateWithKsefToken()`, `startKsefTokenAuthentication()` |
| Authentication | Authentication status | `authenticationStatus()` |
| Authentication | Access / refresh tokens | `redeemAuthenticationTokens()`, `refreshAccessToken()` |
| Online session | Open / close | `openOnlineSession()`, `closeOnlineSession()` |
| Online session | Session status and invoices | `sessionStatus()`, `sessionInvoices()`, `sessionInvoice()` |
| Invoices | Send encrypted invoice | `sendInvoice()` |
| Invoices | Wait for processing | `waitForInvoice()` |
| Invoices | Download UPO | `sessionInvoiceUpo()` |
| Invoices | Download by KSeF number | `invoice()` |
| Invoices | Query metadata | `queryInvoiceMetadata()` |
| KSeF tokens | Generate / list / get / revoke | `generateToken()`, `queryTokens()`, `token()`, `revokeToken()` |

Not supported yet: XAdES signature authentication, batch sessions, invoice exports and permission management.

## Project status

Early development (`0.x`). The public API may change between minor versions until `1.0.0`. Unit test coverage is currently minimal; verify every integration in the Test or Demo environment first.

## Common operations

```php
$sessionStatus = $client->sessionStatus($session->referenceNumber);

$invoice = $client->sessionInvoice(
    $session->referenceNumber,
    $submitted['referenceNumber'],
);

$upoXml = $client->sessionInvoiceUpo(
    $session->referenceNumber,
    $submitted['referenceNumber'],
);

$invoiceXml = $client->invoice('KSeF invoice number');
```

## Architecture

`KsefClient` is the public facade. Each use case is isolated in an action with the following structure:

```text
src/Action/<Action>/<Action>Action.php
src/Action/<Action>/<Action>Request.php
src/Action/<Action>/<Action>Response.php
```

Actions contain the application flow, Request DTOs describe input, and Response DTOs expose typed results. HTTP transport details are kept in the internal `ApiClient` and can be replaced with a custom transport in tests.

## Quality checks

```bash
composer qa
composer cs:fix
composer security:audit
```

The QA pipeline validates Composer metadata, PHP syntax, code style, static analysis and unit tests.

## Security and limitations

- Keep KSeF tokens and private keys outside the repository, preferably in a secret manager or environment variables.
- Use the correct public certificate for each KSeF operation; token encryption and invoice encryption use different certificates.
- The library does not generate invoice XML or XAdES signatures. The supplied XML must comply with the current FA schema published by the Polish Ministry of Finance.
- Test the integration in the Test or Demo environment before using Production.

## License

MIT
