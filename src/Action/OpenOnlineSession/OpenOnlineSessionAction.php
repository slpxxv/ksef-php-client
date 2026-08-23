<?php

declare(strict_types=1);

namespace slpxxv\ksef\Action\OpenOnlineSession;

use slpxxv\ksef\Http\ApiClient;
use slpxxv\ksef\Model\OnlineSession;

final class OpenOnlineSessionAction
{
    public function __construct(private readonly ApiClient $api) {}

    public function execute(OpenOnlineSessionRequest $request): OpenOnlineSessionResponse
    {
        $response = $this->api->requestJson('POST', '/sessions/online', [
            'formCode' => [
                'systemCode' => $request->systemCode,
                'schemaVersion' => $request->schemaVersion,
                'value' => $request->value,
            ],
            'encryption' => [
                'encryptedSymmetricKey' => $request->encryption->encryptedSymmetricKey,
                'initializationVector' => $request->encryption->initializationVector,
                'publicKeyId' => $request->encryption->publicKeyId,
            ],
        ]);

        return new OpenOnlineSessionResponse(OnlineSession::fromArray($response));
    }
}
