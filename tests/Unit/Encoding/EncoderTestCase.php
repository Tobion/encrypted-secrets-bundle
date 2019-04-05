<?php

namespace App\Tests\Unit\SecretsEncryption\Encoding;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use PHPUnit\Framework\TestCase;

abstract class EncoderTestCase extends TestCase
{
    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(BinaryEncoderInterface::class, $this->createEncoder());
    }

    /**
     * @dataProvider provideEncodingData
     */
    public function testEncoding(string $data, string $encoded): void
    {
        $this->assertSame($encoded, $this->createEncoder()->encode($data));
    }

    /**
     * @dataProvider provideEncodingData
     */
    public function testDecoding(string $decoded, string $data): void
    {
        $this->assertSame($decoded, $this->createEncoder()->decode($data));
    }

    /**
     * @dataProvider provideEncodingData
     */
    public function testEncodingAndDecodingPreservesData(string $data): void
    {
        $encoder = $this->createEncoder();

        $this->assertSame($data, $encoder->decode($encoder->encode($data)));
    }

    abstract public function provideEncodingData(): iterable;

    abstract protected function createEncoder(): BinaryEncoderInterface;
}
