<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

/**
 * @see https://tools.ietf.org/html/rfc2152
 */
final class Utf7Encoder implements BinaryEncoderInterface
{
    public function __construct()
    {
        if (!\extension_loaded('mbstring')) {
            throw new \RuntimeException(sprintf('The %s requires the mbstring extension.', self::class));
        }
    }

    public function encode(string $binary): string
    {
        return \mb_convert_encoding($binary, 'UTF-7', '8bit');
    }

    public function decode(string $encoded): string
    {
        return \mb_convert_encoding($encoded, '8bit', 'UTF-7');
    }
}
