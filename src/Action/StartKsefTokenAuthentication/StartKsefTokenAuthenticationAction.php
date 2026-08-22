<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\StartKsefTokenAuthentication;

use slpxxv\ksef\Action\Challenge\ChallengeAction;
use slpxxv\ksef\Action\Challenge\ChallengeRequest;
use slpxxv\ksef\Crypto\Cryptography;
use slpxxv\ksef\Http\ApiClient;

final class StartKsefTokenAuthenticationAction
{
    public function __construct(
        private readonly ApiClient $api,
    ) {}

    public function execute(StartKsefTokenAuthenticationRequest $request): StartKsefTokenAuthenticationResponse
    {
        $challenge = (new ChallengeAction($this->api))->execute(new ChallengeRequest())->typedData();

        return new StartKsefTokenAuthenticationResponse($this->api->requestJson('POST', '/auth/ksef-token', [
            'challenge' => $challenge['challenge'],
            'contextIdentifier' => [
                'type' => $request->contextType,
                'value' => $request->contextValue,
            ],
            'encryptedToken' => Cryptography::encryptRsaOaep(
                $request->token . '|' . $challenge['timestampMs'],
                $request->publicKeyPem,
            ),
            'publicKeyId' => $request->publicKeyId,
        ]));
    }
}
