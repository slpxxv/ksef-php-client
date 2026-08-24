<?php

declare(strict_types=1);

namespace slpxxv\ksef;

use slpxxv\ksef\Action\AuthenticateWithKsefToken\AuthenticateWithKsefTokenAction;
use slpxxv\ksef\Action\AuthenticateWithKsefToken\AuthenticateWithKsefTokenRequest;
use slpxxv\ksef\Action\AuthenticationStatus\AuthenticationStatusAction;
use slpxxv\ksef\Action\AuthenticationStatus\AuthenticationStatusRequest;
use slpxxv\ksef\Action\Challenge\ChallengeAction;
use slpxxv\ksef\Action\Challenge\ChallengeRequest;
use slpxxv\ksef\Action\CloseOnlineSession\CloseOnlineSessionAction;
use slpxxv\ksef\Action\CloseOnlineSession\CloseOnlineSessionRequest;
use slpxxv\ksef\Action\GenerateToken\GenerateTokenAction;
use slpxxv\ksef\Action\GenerateToken\GenerateTokenRequest;
use slpxxv\ksef\Action\GetToken\GetTokenAction;
use slpxxv\ksef\Action\GetToken\GetTokenRequest;
use slpxxv\ksef\Action\Invoice\InvoiceAction;
use slpxxv\ksef\Action\Invoice\InvoiceRequest;
use slpxxv\ksef\Action\OpenOnlineSession\OpenOnlineSessionAction;
use slpxxv\ksef\Action\OpenOnlineSession\OpenOnlineSessionRequest;
use slpxxv\ksef\Action\PublicKeyCertificate\PublicKeyCertificateAction;
use slpxxv\ksef\Action\PublicKeyCertificate\PublicKeyCertificateRequest;
use slpxxv\ksef\Action\PublicKeyCertificates\PublicKeyCertificatesAction;
use slpxxv\ksef\Action\PublicKeyCertificates\PublicKeyCertificatesRequest;
use slpxxv\ksef\Action\QueryInvoiceMetadata\QueryInvoiceMetadataAction;
use slpxxv\ksef\Action\QueryInvoiceMetadata\QueryInvoiceMetadataRequest;
use slpxxv\ksef\Action\QueryTokens\QueryTokensAction;
use slpxxv\ksef\Action\QueryTokens\QueryTokensRequest;
use slpxxv\ksef\Action\RedeemAuthenticationTokens\RedeemAuthenticationTokensAction;
use slpxxv\ksef\Action\RedeemAuthenticationTokens\RedeemAuthenticationTokensRequest;
use slpxxv\ksef\Action\RefreshAccessToken\RefreshAccessTokenAction;
use slpxxv\ksef\Action\RefreshAccessToken\RefreshAccessTokenRequest;
use slpxxv\ksef\Action\RevokeToken\RevokeTokenAction;
use slpxxv\ksef\Action\RevokeToken\RevokeTokenRequest;
use slpxxv\ksef\Action\SendInvoice\SendInvoiceAction;
use slpxxv\ksef\Action\SendInvoice\SendInvoiceRequest;
use slpxxv\ksef\Action\SessionInvoice\SessionInvoiceAction;
use slpxxv\ksef\Action\SessionInvoice\SessionInvoiceRequest;
use slpxxv\ksef\Action\SessionInvoices\SessionInvoicesAction;
use slpxxv\ksef\Action\SessionInvoices\SessionInvoicesRequest;
use slpxxv\ksef\Action\SessionInvoiceUpo\SessionInvoiceUpoAction;
use slpxxv\ksef\Action\SessionInvoiceUpo\SessionInvoiceUpoRequest;
use slpxxv\ksef\Action\SessionStatus\SessionStatusAction;
use slpxxv\ksef\Action\SessionStatus\SessionStatusRequest;
use slpxxv\ksef\Action\StartKsefTokenAuthentication\StartKsefTokenAuthenticationAction;
use slpxxv\ksef\Action\StartKsefTokenAuthentication\StartKsefTokenAuthenticationRequest;
use slpxxv\ksef\Action\WaitForInvoice\WaitForInvoiceAction;
use slpxxv\ksef\Action\WaitForInvoice\WaitForInvoiceRequest;
use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Http\CurlTransport;
use slpxxv\ksef\Http\TransportInterface;
use slpxxv\ksef\Model\AuthTokens;
use slpxxv\ksef\Model\EncryptionData;
use slpxxv\ksef\Model\OnlineSession;

final class KsefClient
{
    private readonly ApiClient $api;

    public function __construct(
        private readonly string             $baseUri,
        private readonly TransportInterface $transport = new CurlTransport(),
        ?string                             $accessToken = null,
        private readonly int                $pollIntervalMilliseconds = 500,
    ) {
        $this->api = new ApiClient($this->baseUri, $this->transport, $accessToken);
    }

    public static function forEnvironment(
        string              $environment,
        ?TransportInterface $transport = null,
        ?string             $accessToken = null,
    ): self {
        return new self(Environment::baseUri($environment), $transport ?? new CurlTransport(), $accessToken);
    }

    public function withAccessToken(string $accessToken): self
    {
        return new self($this->baseUri, $this->transport, $accessToken, $this->pollIntervalMilliseconds);
    }

    /** @return list<array<string, mixed>> */
    public function publicKeyCertificates(): array
    {
        return $this->action(PublicKeyCertificatesAction::class)
            ->execute(new PublicKeyCertificatesRequest())
            ->certificates;
    }

    /** @return array<string, mixed> */
    public function publicKeyCertificate(string $usage): array
    {
        return $this->action(PublicKeyCertificateAction::class)
            ->execute(new PublicKeyCertificateRequest($usage))
            ->certificate;
    }

    /**
     * @template T of object
     * @param class-string<T> $actionClass
     * @return T
     */
    private function action(string $actionClass): object
    {
        return new $actionClass($this->api);
    }

    /** @return array{challenge: string, timestampMs: int, timestamp: string, clientIp?: string} */
    public function challenge(): array
    {
        return $this->action(ChallengeAction::class)
            ->execute(new ChallengeRequest())
            ->typedData();
    }

    /** @return array{referenceNumber: string, authenticationToken: array{token: string, validUntil: string}} */
    public function startKsefTokenAuthentication(
        string $token,
        string $contextType,
        string $contextValue,
        string $publicKeyPem,
        string $publicKeyId,
    ): array {
        return $this->action(StartKsefTokenAuthenticationAction::class)->execute(new StartKsefTokenAuthenticationRequest(
            token: $token,
            contextType: $contextType,
            contextValue: $contextValue,
            publicKeyPem: $publicKeyPem,
            publicKeyId: $publicKeyId,
        ))->typedData();
    }

    /** @return array<string, mixed> */
    public function authenticationStatus(string $referenceNumber, string $authenticationToken): array
    {
        return $this->action(AuthenticationStatusAction::class)->execute(new AuthenticationStatusRequest(
            referenceNumber: $referenceNumber,
            authenticationToken: $authenticationToken,
        ))->data;
    }

    public function redeemAuthenticationTokens(string $authenticationToken): AuthTokens
    {
        return $this->action(RedeemAuthenticationTokensAction::class)->execute(
            new RedeemAuthenticationTokensRequest($authenticationToken),
        )->tokens;
    }

    public function authenticateWithKsefToken(
        string $token,
        string $contextType,
        string $contextValue,
        string $publicKeyPem,
        string $publicKeyId,
        int    $timeoutSeconds = 60,
    ): AuthTokens {
        return $this->action(AuthenticateWithKsefTokenAction::class)->execute(new AuthenticateWithKsefTokenRequest(
            token: $token,
            contextType: $contextType,
            contextValue: $contextValue,
            publicKeyPem: $publicKeyPem,
            publicKeyId: $publicKeyId,
            timeoutSeconds: $timeoutSeconds,
            pollIntervalMilliseconds: $this->pollIntervalMilliseconds,
        ))->tokens;
    }

    public function refreshAccessToken(string $refreshToken): AuthTokens
    {
        return $this->action(RefreshAccessTokenAction::class)
            ->execute(new RefreshAccessTokenRequest($refreshToken))
            ->tokens;
    }

    public function openOnlineSession(
        EncryptionData $encryption,
        string         $systemCode = 'FA (3)',
        string         $schemaVersion = '1-0E',
        string         $value = 'FA',
    ): OnlineSession {
        return $this->action(OpenOnlineSessionAction::class)->execute(new OpenOnlineSessionRequest(
            encryption: $encryption,
            systemCode: $systemCode,
            schemaVersion: $schemaVersion,
            value: $value,
        ))->session;
    }

    /** @return array{referenceNumber: string} */
    public function sendInvoice(string|OnlineSession $session, string $invoiceXml, EncryptionData $encryption): array
    {
        return $this->action(SendInvoiceAction::class)
            ->execute(new SendInvoiceRequest($session, $invoiceXml, $encryption))
            ->data;
    }

    public function closeOnlineSession(string|OnlineSession $session): void
    {
        $this->action(CloseOnlineSessionAction::class)->execute(new CloseOnlineSessionRequest($session));
    }

    /** @return array<string, mixed> */
    public function sessionStatus(string $sessionReference): array
    {
        return $this->action(SessionStatusAction::class)
            ->execute(new SessionStatusRequest($sessionReference))
            ->data;
    }

    /** @return array<string, mixed> */
    public function sessionInvoices(string $sessionReference, ?string $continuationToken = null, ?int $pageSize = null): array
    {
        return $this->action(SessionInvoicesAction::class)->execute(new SessionInvoicesRequest(
            sessionReference: $sessionReference,
            continuationToken: $continuationToken,
            pageSize: $pageSize,
        ))->data;
    }

    /** @return array<string, mixed> */
    public function sessionInvoice(string $sessionReference, string $invoiceReferenceNumber): array
    {
        return $this->action(SessionInvoiceAction::class)
            ->execute(new SessionInvoiceRequest($sessionReference, $invoiceReferenceNumber))
            ->data;
    }

    /** @return array<string, mixed> */
    public function waitForInvoice(
        string $sessionReference,
        string $invoiceReferenceNumber,
        int $timeoutSeconds = 120,
        int $pollIntervalMilliseconds = 1000,
    ): array {
        return $this->action(WaitForInvoiceAction::class)->execute(new WaitForInvoiceRequest(
            sessionReference: $sessionReference,
            invoiceReferenceNumber: $invoiceReferenceNumber,
            timeoutSeconds: $timeoutSeconds,
            pollIntervalMilliseconds: $pollIntervalMilliseconds,
        ))->data;
    }

    public function sessionInvoiceUpo(string $sessionReference, string $invoiceReferenceNumber): string
    {
        return $this->action(SessionInvoiceUpoAction::class)
            ->execute(new SessionInvoiceUpoRequest($sessionReference, $invoiceReferenceNumber))
            ->content;
    }

    public function invoice(string $ksefNumber): string
    {
        return $this->action(InvoiceAction::class)
            ->execute(new InvoiceRequest($ksefNumber))
            ->content;
    }

    /**
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function queryInvoiceMetadata(array $filters): array
    {
        return $this->action(QueryInvoiceMetadataAction::class)
            ->execute(new QueryInvoiceMetadataRequest($filters))
            ->data;
    }

    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function generateToken(array $request): array
    {
        return $this->action(GenerateTokenAction::class)
            ->execute(new GenerateTokenRequest($request))
            ->data;
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    public function queryTokens(array $query = []): array
    {
        return $this->action(QueryTokensAction::class)
            ->execute(new QueryTokensRequest($query))
            ->data;
    }

    /** @return array<string, mixed> */
    public function token(string $referenceNumber): array
    {
        return $this->action(GetTokenAction::class)
            ->execute(new GetTokenRequest($referenceNumber))
            ->data;
    }

    public function revokeToken(string $referenceNumber): void
    {
        $this->action(RevokeTokenAction::class)->execute(new RevokeTokenRequest($referenceNumber));
    }
}
