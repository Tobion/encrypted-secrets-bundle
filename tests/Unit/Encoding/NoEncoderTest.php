<?php

namespace App\Tests\Unit\SecretsEncryption\Encoding;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use App\SecretsEncryption\Encoding\NoEncoder;

class NoEncoderTest extends EncoderTestCase
{
    public function provideEncodingData(): iterable
    {
        yield ['', ''];
        yield ['foo', 'foo'];
        yield ['€', '€']; // data as unicode
        yield ["\x80\x81", "\x80\x81"]; // invalid unicode

        $allBytesString = implode('', array_map(function (int $byte) {
            return \chr($byte);
        }, range(0, 255)));

        yield [$allBytesString, $allBytesString];

        $random = random_bytes(10);

        yield [$random, $random];
    }

    protected function createEncoder(): BinaryEncoderInterface
    {
        return new NoEncoder();
    }
}
