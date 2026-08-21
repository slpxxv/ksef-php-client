<?php

declare(strict_types=1);

namespace slpxxv\ksef\Tests\Unit;

use PHPUnit\Framework\TestCase;
use slpxxv\ksef\Environment;

final class EnvironmentTest extends TestCase
{
    public function testTestEnvironmentHasExpectedApiUri(): void
    {
        self::assertSame(
            'https://api-test.ksef.mf.gov.pl/v2',
            Environment::baseUri(Environment::TEST),
        );
    }
}
