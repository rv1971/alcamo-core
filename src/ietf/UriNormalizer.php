<?php

namespace alcamo\ietf;

use GuzzleHttp\Psr7\UriNormalizer as GuzzleHttpUriNormalizer;
use Psr\Http\Message\UriInterface;

class UriNormalizer
{
    /**
     * Apply realpath() to file:/// URIs. On Windows platforms this has no
     * effect.
     */
    public const APPLY_REALPATH = 0x8000;

    public static function normalize(
        UriInterface $uri,
        $flags = null,
        ?string $osFamily = null
    ): UriInterface {
        if (!isset($flags)) {
            $flags = GuzzleHttpUriNormalizer::PRESERVING_NORMALIZATIONS
                | self::APPLY_REALPATH;
        }

        $uri = GuzzleHttpUriNormalizer::normalize($uri, $flags);

        if (
            $flags & self::APPLY_REALPATH
            && $uri->getScheme() == 'file'
            && $uri->getHost() == ''
            && ($osFamily ?? PHP_OS_FAMILY) != 'Windows'
        ) {
            return $uri->withPath(realpath($uri->getPath()));
        }

        return $uri;
    }
}
