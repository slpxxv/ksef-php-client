<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\AuthenticateWithKsefToken;

use InvalidArgumentException;
use RuntimeException;
use slpxxv\ksef\Action\AuthenticationStatus\AuthenticationStatusAction;
use slpxxv\ksef\Action\AuthenticationStatus\AuthenticationStatusRequest;
use slpxxv\ksef\Action\RedeemAuthenticationTokens\RedeemAuthenticationTokensAction;
use slpxxv\ksef\Action\RedeemAuthenticationTokens\RedeemAuthenticationTokensRequest;
use slpxxv\ksef\Action\StartKsefTokenAuthentication\StartKsefTokenAuthenticationAction;
use slpxxv\ksef\Action\StartKsefTokenAuthentication\StartKsefTokenAuthenticationRequest;
use slpxxv\ksef\Http\ApiClient;

final class AuthenticateWithKsefTokenAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(AuthenticateWithKsefTokenRequest $request): AuthenticateWithKsefTokenResponse
    {
        if ($request->timeoutSeconds < 0) {
            throw new InvalidArgumentException('Authentication timeout cannot be negative.');
        }
        if ($request->pollIntervalMilliseconds < 0) {
            throw new InvalidArgumentException('Polling interval cannot be negative.');
        }

        $start = new StartKsefTokenAuthenticationAction($this->api);
        $status = new AuthenticationStatusAction($this->api);
        $redeem = new RedeemAuthenticationTokensAction($this->api);

        $initialization = $start->execute(new StartKsefTokenAuthenticationRequest(
            token: $request->token,
            contextType: $request->contextType,
            contextValue: $request->contextValue,
            publicKeyPem: $request->publicKeyPem,
            publicKeyId: $request->publicKeyId,
        ))->typedData();
        $operationToken = (string) $initialization['authenticationToken']['token'];
        $deadline = microtime(true) + $request->timeoutSeconds;

        do {
            $statusResponse = $status->execute(new AuthenticationStatusRequest(
                referenceNumber: (string) $initialization['referenceNumber'],
                authenticationToken: $operationToken,
            ));
            $response = $statusResponse->data;
            $code = $statusResponse->code();

            if ($code === 200) {
                return new AuthenticateWithKsefTokenResponse(
                    $redeem->execute(new RedeemAuthenticationTokensRequest($operationToken))->tokens,
                );
            }

            if ($code !== 100) {
                $description = (string) ($response['status']['description'] ?? 'Authentication failed.');
                $details = $response['status']['details'] ?? [];
                $suffix = $details === [] ? '' : ' ' . implode(' ', (array) $details);
                throw new RuntimeException(trim($description . $suffix));
            }

            if (microtime(true) < $deadline) {
                usleep($request->pollIntervalMilliseconds * 1000);
            }
        } while (microtime(true) < $deadline);

        throw new RuntimeException('KSeF authentication timed out.');
    }
}
