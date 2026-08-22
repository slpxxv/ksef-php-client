<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\PublicKeyCertificate;

use slpxxv\ksef\Action\PublicKeyCertificates\PublicKeyCertificatesAction;
use slpxxv\ksef\Action\PublicKeyCertificates\PublicKeyCertificatesRequest;

final class PublicKeyCertificateAction
{
    public function __construct(private readonly \slpxxv\ksef\Http\ApiClient $api) {}

    public function execute(PublicKeyCertificateRequest $request): PublicKeyCertificateResponse
    {
        $certificates = (new PublicKeyCertificatesAction($this->api))
            ->execute(new PublicKeyCertificatesRequest())
            ->certificates;
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $validCertificates = [];

        foreach ($certificates as $certificate) {
            $usages = is_array($certificate['usage'] ?? null) ? $certificate['usage'] : [];
            if (!in_array($request->usage, $usages, true)) {
                continue;
            }

            try {
                $validFrom = new \DateTimeImmutable((string) $certificate['validFrom']);
                $validTo = new \DateTimeImmutable((string) $certificate['validTo']);
            } catch (\Exception) {
                continue;
            }

            if ($now < $validFrom || $now > $validTo) {
                continue;
            }

            $validCertificates[] = $certificate;
        }

        usort($validCertificates, function (array $left, array $right): int {
            return strcmp((string) ($right['validFrom'] ?? ''), (string) ($left['validFrom'] ?? ''));
        });

        if ($validCertificates === []) {
            throw new \RuntimeException("No active KSeF public certificate found for usage '{$request->usage}'.");
        }

        return new PublicKeyCertificateResponse($validCertificates[0]);
    }
}
