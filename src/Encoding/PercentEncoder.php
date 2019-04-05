<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

/**
 * @see https://tools.ietf.org/html/rfc3986
 */
final class PercentEncoder implements BinaryEncoderInterface
{
    public function encode(string $binary): string
    {
        return rawurlencode($binary);
    }

    public function decode(string $encoded): string
    {
        return rawurldecode($encoded);
    }
}
