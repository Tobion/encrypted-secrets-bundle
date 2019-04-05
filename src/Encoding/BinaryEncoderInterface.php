<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

interface BinaryEncoderInterface
{
    public function encode(string $binary): string;

    public function decode(string $encoded): string;
}
