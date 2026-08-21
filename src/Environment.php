<?php

declare(strict_types=1);

namespace slpxxv\ksef;

use InvalidArgumentException;

final class Environment
{
    public const TEST = 'test';
    public const DEMO = 'demo';
    public const PRODUCTION = 'production';

    public static function baseUri(string $environment): string
    {
        return match ($environment) {
            self::TEST => 'https://api-test.ksef.mf.gov.pl/v2',
            self::DEMO => 'https://api-demo.ksef.mf.gov.pl/v2',
            self::PRODUCTION => 'https://api.ksef.mf.gov.pl/v2',
            default => throw new InvalidArgumentException("Unknown KSeF environment: {$environment}"),
        };
    }
}
