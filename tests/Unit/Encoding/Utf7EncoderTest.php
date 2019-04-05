<?php

namespace App\Tests\Unit\SecretsEncryption\Encoding;

use App\SecretsEncryption\Encoding\BinaryEncoderInterface;
use App\SecretsEncryption\Encoding\Utf7Encoder;

class Utf7EncoderTest extends EncoderTestCase
{
    public function provideEncodingData(): iterable
    {
        yield ['', ''];
        yield ['f', 'f'];
        yield ['foobar', 'foobar'];
        yield ['€', '+AOIAggCs-']; // data as unicode
        yield ['Übergröße', '+AMMAnA-bergr+AMMAtgDDAJ8-e'];
        yield ["\x80\x81", '+AIAAgQ-']; // invalid unicode

        $allBytesString = implode('', array_map(function (int $byte) {
            return \chr($byte);
        }, range(0, 255)));

        yield [
            $allBytesString,
            rawurldecode(
                '%00%2BAAEAAgADAAQABQAGAAcACA%09%0A%2BAAsADA%0D%2BAA4ADwAQABEAEgATABQAFQAWABcAGAAZABoAGwAcAB0AHgAf%20%2BACEAIgAjACQAJQAm%27%28%29%2BACoAKw%2C-.%2F0123456789'.
                '%3A%2BADsAPAA9AD4%3F%2BAEA-ABCDEFGHIJKLMNOPQRSTUVWXYZ%2BAFsAXABdAF4AXwBg-abcdefghijklmnopqrstuvwxyz%2BAHsAfAB9AH4AfwCAAIEAggCDAIQAhQCGAIcAiACJAIoAiwCMAI0Aj'.
                'gCPAJAAkQCSAJMAlACVAJYAlwCYAJkAmgCbAJwAnQCeAJ8AoAChAKIAowCkAKUApgCnAKgAqQCqAKsArACtAK4ArwCwALEAsgCzALQAtQC2ALcAuAC5ALoAuwC8AL0AvgC%2FAMAAwQDCAMMAxADFAMYAxw'.
                'DIAMkAygDLAMwAzQDOAM8A0ADRANIA0wDUANUA1gDXANgA2QDaANsA3ADdAN4A3wDgAOEA4gDjAOQA5QDmAOcA6ADpAOoA6wDsAO0A7gDvAPAA8QDyAPMA9AD1APYA9wD4APkA%2BgD7APwA%2FQD%2BAP8-'
            ),
        ];
    }

    protected function createEncoder(): BinaryEncoderInterface
    {
        return new Utf7Encoder();
    }
}
