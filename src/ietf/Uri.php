<?php

namespace alcamo\ietf;

use GuzzleHttp\Psr7\Uri as GuzzleHttpUri;

class Uri extends GuzzleHttpUri
{
    public static function newFromFilesystemPath(
        string $path,
        ?bool $prependScheme = null,
        ?string $osFamily = null
    ): self {
        if (($osFamily ?? PHP_OS_FAMILY) == 'Windows') {
            // replace directory separator by slash
            $uri = str_replace('\\', '/', $path);

            // if path contains drive, prepend slash
            if ($uri[1] == ':') {
                $uri = "/$uri";
            }
        } else {
            $uri = $path;
        }

        /* If absolute and $prependScheme, prepend `file:`. Due to the way
         * GuzzleHttp\Psr7\Uri parses and re-assembles URIs, `file:/` becomes
         * `file:///` in _-toString. */
        if ($uri[0] == '/' && $prependScheme) {
            $uri = "file:$uri";
        }

        return new self($uri);
    }
}
