<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

use Base32\Base32;

/**
 * @see https://tools.ietf.org/html/rfc4648#section-6
 */
final class Base32Encoder implements BinaryEncoderInterface
{
    public function __construct()
    {
        if (!\class_exists(Base32::class)) {
            throw new \RuntimeException(sprintf('The %s requires the %s class. Please run "composer require christian-riesen/base32".', self::class, Base32::class));
        }
    }

    public function encode(string $binary): string
    {
        return Base32::encode($binary);
    }

    public function decode(string $encoded): string
    {
        return Base32::decode($encoded);
    }
}
