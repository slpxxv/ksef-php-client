<?php

declare(strict_types=1);

namespace slpxxv\ksef\Http;

use slpxxv\ksef\Exception\TransportException;

final class CurlTransport implements TransportInterface
{
    public function __construct(
        private readonly int  $connectTimeout = 10,
        private readonly int  $timeout = 60,
        private readonly bool $verifyTls = true,
    ) {
        if (!extension_loaded('curl')) {
            throw new TransportException('The cURL PHP extension is required.');
        }
    }

    /** @param array<string, string> $headers */
    public function request(string $method, string $url, array $headers = [], ?string $body = null): Response
    {
        if ($url === '') {
            throw new TransportException('KSeF request URL cannot be empty.');
        }
        $method = strtoupper($method);
        if ($method === '') {
            throw new TransportException('KSeF HTTP method cannot be empty.');
        }

        $handle = curl_init($url);
        if ($handle === false) {
            throw new TransportException('Unable to initialize cURL.');
        }

        $headerLines = [];
        foreach ($headers as $name => $value) {
            $headerLines[] = $name . ': ' . $value;
        }

        curl_setopt_array($handle, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => $this->verifyTls,
            CURLOPT_SSL_VERIFYHOST => $this->verifyTls ? 2 : 0,
        ]);

        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        }

        $raw = curl_exec($handle);
        if (!is_string($raw)) {
            $error = curl_error($handle);
            curl_close($handle);
            throw new TransportException('KSeF request failed: ' . $error);
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $headerSize = (int) curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        curl_close($handle);

        $rawHeaders = substr($raw, 0, $headerSize);
        $responseBody = substr($raw, $headerSize);

        return new Response($statusCode, $this->parseHeaders($rawHeaders), $responseBody);
    }

    /** @return array<string, string> */
    private function parseHeaders(string $rawHeaders): array
    {
        $blocks = preg_split('/\\r?\\n\\r?\\n/', trim($rawHeaders)) ?: [];
        $lastBlock = $blocks === [] ? '' : (string) end($blocks);
        $headers = [];

        foreach (preg_split('/\\r?\\n/', $lastBlock) ?: [] as $line) {
            if (!str_contains($line, ':')) {
                continue;
            }

            [$name, $value] = explode(':', $line, 2);
            $headers[strtolower(trim($name))] = trim($value);
        }

        return $headers;
    }
}
