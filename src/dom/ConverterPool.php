<?php

namespace alcamo\dom;

use alcamo\binary_data\BinaryString;
use alcamo\iana\MediaType;
use alcamo\ietf\{Lang, Uri};
use alcamo\time\Duration;
use alcamo\xml\XName;

class ConverterPool
{
    public static function toArray($value): array
    {
        return preg_split('/\s+/', $value);
    }

    public static function toBool($value): bool
    {
        return $value == 'true';
    }

    public static function toDateTime($value): \DateTime
    {
        return new \DateTime($value);
    }

    public static function toDuration($value): Duration
    {
        return new Duration($value);
    }

    public static function toFloat($value): float
    {
        return (float)$value;
    }

    /// Convert to integer if value can be represented as int
    public static function toInt($value)
    {
        if (is_int($value + 0)) {
            return (int)$value;
        } else {
            return $value;
        }
    }

    public static function toLang($value): Lang
    {
        return Lang::newFromString($value);
    }

    public static function toMediaType($value): MediaType
    {
        return MediaType::newFromString($value);
    }

    public static function toUri($value): Uri
    {
        return new Uri($value);
    }

    public static function toXName($value, $context): XName
    {
        return XName::newFromQNameAndContext($value, $context);
    }

    public static function toXNames($value, $context): array
    {
        $xNames = [];

        foreach (preg_split('/\s+/', $value) as $item) {
            $xNames[] = XName::newFromQNameAndContext($item, $context);
        }

        return $xNames;
    }

    public static function base64ToBinary($value): BinaryString
    {
        return new BinaryString(base64_decode($value));
    }

    public static function hexToBinary($value): BinaryString
    {
        return new BinaryString(hex2bin($value));
    }

    public static function curieToUri($value, $context): Uri
    {
        return Uri::newFromCurieAndContext($value, $context);
    }

    public static function safeCurieToUri($value, $context): Uri
    {
        return Uri::newFromSafeCurieAndContext($value, $context);
    }

    public static function uriOrSafeCurieToUri($value, $context): Uri
    {
        return Uri::newFromUriOrSafeCurieAndContext($value, $context);
    }
}
