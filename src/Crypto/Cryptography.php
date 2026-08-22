<?php

declare(strict_types=1);

namespace slpxxv\ksef\Crypto;

use InvalidArgumentException;
use phpseclib3\Crypt\RSA;
use RuntimeException;

final class Cryptography
{
    /** @return array{key: string, iv: string, encryptedSymmetricKey: string, initializationVector: string, publicKeyId: string} */
    public static function createEncryptionData(string $publicKeyPem, string $publicKeyId): array
    {
        $key = random_bytes(32);
        $iv = random_bytes(16);

        return [
            'key' => $key,
            'iv' => $iv,
            'encryptedSymmetricKey' => self::encryptRsaOaep($key, $publicKeyPem),
            'initializationVector' => base64_encode($iv),
            'publicKeyId' => $publicKeyId,
        ];
    }

    public static function encryptRsaOaep(string $plaintext, string $publicKeyPem): string
    {
        $key = RSA::load($publicKeyPem);
        if (!$key instanceof RSA) {
            throw new RuntimeException('The supplied key is not an RSA key.');
        }

        $key = $key
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256');

        return base64_encode($key->encrypt($plaintext));
    }

    public static function encryptAes256Cbc(string $plaintext, string $key, string $iv): string
    {
        if (strlen($key) !== 32 || strlen($iv) !== 16) {
            throw new InvalidArgumentException('AES-256-CBC requires a 32-byte key and a 16-byte IV.');
        }

        $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new RuntimeException('Unable to encrypt data with AES-256-CBC.');
        }

        return $encrypted;
    }

    public static function sha256Base64(string $data): string
    {
        return base64_encode(hash('sha256', $data, true));
    }
}
