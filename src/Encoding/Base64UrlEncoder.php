<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

/**
 * @see https://tools.ietf.org/html/rfc4648#section-5
 */
final class Base64UrlEncoder implements BinaryEncoderInterface
{
    public function encode(string $binary): string
    {
        return \strtr(\base64_encode($binary), '+/', '-_');
    }

    public function decode(string $encoded): string
    {
        $decoded = \base64_decode(\strtr($encoded, '-_', '+/'), true);

        if (false === $decoded) {
            throw new \InvalidArgumentException('Invalid base64 encoded value.');
        }

        return $decoded;
    }
}
