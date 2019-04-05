<?php

namespace App\Tests\Unit\SecretsEncryption\Encoding;

use App\SecretsEncryption\Encoding\Base64Encoder;
use App\SecretsEncryption\Encoding\Base64UrlEncoder;
use App\SecretsEncryption\Encoding\EncoderFactory;
use App\SecretsEncryption\Encoding\HexEncoder;
use App\SecretsEncryption\Encoding\NoEncoder;
use App\SecretsEncryption\Encoding\PercentEncoder;
use App\SecretsEncryption\Encoding\SodiumHexEncoder;
use App\SecretsEncryption\Encoding\Utf7Encoder;
use PHPUnit\Framework\TestCase;

class EncoderFactoryTest extends TestCase
{
    public function testEncoding(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown encoding name "foobar"');
        EncoderFactory::createEncoderFromName('foobar');
    }

    /**
     * @dataProvider provideEncodingNamesAndClass
     */
    public function testCreateEncoderFromName(string $name, string $class): void
    {
        $this->assertInstanceOf($class, EncoderFactory::createEncoderFromName($name));
    }

    public function testGetAvailableEncodings(): void
    {
        $this->assertSame(['hex', 'base64', 'base64url', 'percent', 'none'], EncoderFactory::getAvailableEncodings());
    }

    public function provideEncodingNamesAndClass(): iterable
    {
        yield ['hex', \extension_loaded('sodium') ? SodiumHexEncoder::class : HexEncoder::class];
        yield ['base16', \extension_loaded('sodium') ? SodiumHexEncoder::class : HexEncoder::class];
        yield ['base64', Base64Encoder::class];
        yield ['base64url', Base64UrlEncoder::class];
        yield ['percent', PercentEncoder::class];
        yield ['UTF-7', Utf7Encoder::class];
        yield ['none', NoEncoder::class];
    }
}
