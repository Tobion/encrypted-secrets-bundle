<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

final class SodiumHexEncoder implements BinaryEncoderInterface
{
    public function __construct()
    {
        if (!\extension_loaded('sodium')) {
            throw new \RuntimeException(sprintf('The %s requires the sodium extension that is included in PHP since version 7.2.', self::class));
        }
    }

    public function encode(string $binary): string
    {
        return \sodium_bin2hex($binary);
    }

    public function decode(string $encoded): string
    {
        return \sodium_hex2bin($encoded);
    }
}
