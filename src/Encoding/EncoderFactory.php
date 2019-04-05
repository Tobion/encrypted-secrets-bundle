<?php

declare(strict_types=1);

namespace App\SecretsEncryption\Encoding;

use Base32\Base32;

final class EncoderFactory
{
    public const NONE = 'none';
    public const HEX = 'hex';
    public const BASE16 = 'base16';
    public const BASE32 = 'base32';
    public const BASE64 = 'base64';
    public const BASE64URL = 'base64url';
    public const PERCENT = 'percent';
    public const UTF7 = 'UTF-7';

    public static function createEncoderFromName(string $name): BinaryEncoderInterface
    {
        switch ($name) {
            case self::HEX:
            case self::BASE16:
                return \extension_loaded('sodium') ? new SodiumHexEncoder() : new HexEncoder();
            case self::BASE32:
                return new Base32Encoder();
            case self::BASE64:
                return new Base64Encoder();
            case self::BASE64URL:
                return new Base64UrlEncoder();
            case self::PERCENT:
                return new PercentEncoder();
            case self::UTF7:
                return new Utf7Encoder();
            case self::NONE:
                return new NoEncoder();
        }

        throw new \InvalidArgumentException(sprintf('Unknown encoding name "%s"', $name));
    }

    public static function getAvailableEncodings(bool $includeUncommon = false): array
    {
        $encodings = [self::HEX];

        if (class_exists(Base32::class)) {
            $encodings[] = self::BASE32;
        }

        $encodings[] = self::BASE64;
        $encodings[] = self::BASE64URL;
        $encodings[] = self::PERCENT;

        if ($includeUncommon) {
            $encodings[] = self::UTF7;
        }

        $encodings[] = self::NONE;

        return $encodings;
    }
}
