<?php

declare(strict_types=1);

namespace slpxxv\ksef\Http;

use JsonException;
use slpxxv\ksef\Exception\ApiException;

/** Low-level HTTP adapter used by actions. It knows transport details, not use cases. */
final class ApiClient
{
    public function __construct(
        private readonly string             $baseUri,
        private readonly TransportInterface $transport,
        private ?string                     $accessToken = null,
    ) {}

    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, mixed> $query
     * @param array<string, string> $extraHeaders
     * @return array<string, mixed>
     */
    public function requestJson(
        string  $method,
        string  $path,
        ?array  $payload = null,
        ?string $bearer = null,
        array   $query = [],
        array   $extraHeaders = [],
    ): array {
        $response = $this->request($method, $path, $payload, $bearer, $query, $extraHeaders);
        if ($response->body === '') {
            return [];
        }

        try {
            $decoded = $response->json();
        } catch (JsonException $exception) {
            throw new ApiException(
                'KSeF returned invalid JSON.',
                $response->statusCode,
                $response->headers,
                $response->body,
                $exception,
            );
        }

        if (!is_array($decoded)) {
            throw new ApiException('KSeF returned an unexpected JSON payload.', $response->statusCode, $response->headers, $decoded);
        }

        return $decoded;
    }

    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, mixed> $query
     * @param array<string, string> $extraHeaders
     */
    private function request(
        string  $method,
        string  $path,
        ?array  $payload = null,
        ?string $bearer = null,
        array   $query = [],
        array   $extraHeaders = [],
    ): Response {
        $url = rtrim($this->baseUri, '/') . '/' . ltrim($path, '/');
        if ($query !== []) {
            $url .= '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }

        $headers = array_merge([
            'Accept' => 'application/json',
            'X-Error-Format' => 'problem-details',
        ], $extraHeaders);
        $token = $bearer ?? $this->accessToken;
        if ($token !== null) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $body = null;
        if ($payload !== null) {
            $headers['Content-Type'] = 'application/json';
            $body = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        }

        $response = $this->transport->request($method, $url, $headers, $body);
        if ($response->statusCode < 200 || $response->statusCode >= 300) {
            throw $this->createApiException($response);
        }

        return $response;
    }

    private function createApiException(Response $response): ApiException
    {
        $payload = null;
        if ($response->body !== '') {
            try {
                $payload = json_decode($response->body, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                $payload = $response->body;
            }
        }

        $message = is_array($payload)
            ? (string) ($payload['detail'] ?? $payload['title'] ?? 'KSeF API request failed.')
            : 'KSeF API request failed.';

        return new ApiException($message, $response->statusCode, $response->headers, $payload);
    }

    public function requestBinary(string $method, string $path): string
    {
        return $this->request($method, $path)->body;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }
}
