<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

final class NoEncoder implements BinaryEncoderInterface
{
    public function encode(string $binary): string
    {
        return $binary;
    }

    public function decode(string $encoded): string
    {
        return $encoded;
    }
}
