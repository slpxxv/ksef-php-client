<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\PublicKeyCertificates;

use slpxxv\ksef\Http\ApiClient;

final class PublicKeyCertificatesAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(PublicKeyCertificatesRequest $request): PublicKeyCertificatesResponse
    {
        $response = $this->api->requestJson('GET', '/security/public-key-certificates');
        $certificates = [];

        foreach ($response as $certificate) {
            if (!is_array($certificate)) {
                continue;
            }

            $normalized = [];
            foreach ($certificate as $key => $value) {
                if (is_string($key)) {
                    $normalized[$key] = $value;
                }
            }
            $certificates[] = $normalized;
        }

        return new PublicKeyCertificatesResponse($certificates);
    }
}
