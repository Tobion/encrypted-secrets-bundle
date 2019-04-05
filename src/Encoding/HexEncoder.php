<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

/**
 * @see https://tools.ietf.org/html/rfc4648#section-8
 */
final class HexEncoder implements BinaryEncoderInterface
{
    public function encode(string $binary): string
    {
        return \bin2hex($binary);
    }

    public function decode(string $encoded): string
    {
        $decoded = \hex2bin($encoded);

        if (false === $decoded) {
            throw new \InvalidArgumentException('Invalid hex encoded value.');
        }

        return $decoded;
    }
}
