<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

/**
 * @see https://tools.ietf.org/html/rfc4648#section-4
 */
final class Base64Encoder implements BinaryEncoderInterface
{
    public function encode(string $binary): string
    {
        return \base64_encode($binary);
    }

    public function decode(string $encoded): string
    {
        $decoded = \base64_decode($encoded, true);

        if (false === $decoded) {
            throw new \InvalidArgumentException('Invalid base64 encoded value.');
        }

        return $decoded;
    }
}
