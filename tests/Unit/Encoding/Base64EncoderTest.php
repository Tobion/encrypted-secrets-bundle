<?php

namespace App\Tests\Unit\SecretsEncryption\Encoding;

use App\SecretsEncryption\Encoding\Base64Encoder;
use App\SecretsEncryption\Encoding\BinaryEncoderInterface;

class Base64EncoderTest extends EncoderTestCase
{
    public function provideEncodingData(): iterable
    {
        yield ['', ''];
        yield ['f', 'Zg=='];
        yield ['fo', 'Zm8='];
        yield ['foo', 'Zm9v'];
        yield ['foob', 'Zm9vYg=='];
        yield ['fooba', 'Zm9vYmE='];
        yield ['foobar', 'Zm9vYmFy'];
        yield ['€', '4oKs']; // data as unicode
        yield ["\x80\x81", 'gIE=']; // invalid unicode

        $allBytesString = implode('', array_map(function (int $byte) {
            return \chr($byte);
        }, range(0, 255)));

        yield [
            $allBytesString,
            'AAECAwQFBgcICQoLDA0ODxAREhMUFRYXGBkaGxwdHh8gISIjJCUmJygpKissLS4vMDEyMzQ1Njc4OTo7PD0+P0BBQkNERUZHSElKS0xNTk9QUVJTVF'.
            'VWV1hZWltcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6e3x9fn+AgYKDhIWGh4iJiouMjY6PkJGSk5SVlpeYmZqbnJ2en6ChoqOkpaanqKmq'.
            'q6ytrq+wsbKztLW2t7i5uru8vb6/wMHCw8TFxsfIycrLzM3Oz9DR0tPU1dbX2Nna29zd3t/g4eLj5OXm5+jp6uvs7e7v8PHy8/T19vf4+fr7/P3+/w==',
        ];
    }

    protected function createEncoder(): BinaryEncoderInterface
    {
        return new Base64Encoder();
    }
}
