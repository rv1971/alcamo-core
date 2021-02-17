<?php

namespace alcamo\ietf;

use GuzzleHttp\Psr7\Uri as GuzzleHttpUri;
use alcamo\exception\SyntaxError;
use alcamo\xml\exception\UnknownNamespacePrefix;

/**
 * @sa [CURIE Syntax 1.0](https://www.w3.org/TR/curie/)
 */
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
         * `file:///` in __toString. */
        if ($uri[0] == '/' && ($prependScheme ?? true)) {
            $uri = "file:$uri";
        }

        return new self($uri);
    }

    /**
     * @brief Create from URI or safe CURIE and prefix map.
     *
     * @param $uriOrSafeCurie string URI or safe CURIE.
     *
     * @param $map array|ArrayAccess Map of prefixes to values.
     *
     * @param $defaultPrefixValue string|null Default prefix value to add to
     * unprefixed names.
     */
    public static function newFromUriOrSafeCurieAndMap(
        string $uriOrSafeCurie,
        $map,
        ?string $defaultPrefixValue = null
    ): self {
        return $uriOrSafeCurie[0] == '['
            ? self::newFromSafeCurieAndMap(
                $uriOrSafeCurie,
                $map,
                $defaultPrefixValue
            )
            : new self($uriOrSafeCurie);
    }

    /**
     * @brief Create from URI or safe CURIE and DOM context node.
     *
     * @param $uriOrSafeCurie string URI or safe CURIE.
     *
     * @param $context DOMNode Context node.
     *
     * @param $defaultPrefixValue string|null Default prefix value to add to
     * unprefixed names. If not provided, the context's default namespace is
     * used.
     */
    public static function newFromUriOrSafeCurieAndContext(
        string $uriOrSafeCurie,
        \DOMNode $context,
        ?string $defaultPrefixValue = null
    ): self {
        return $uriOrSafeCurie[0] == '['
            ? self::newFromSafeCurieAndContext(
                $uriOrSafeCurie,
                $context,
                $defaultPrefixValue
            )
            : new self($uriOrSafeCurie, null);
    }

    /**
     * @brief Create from safe CURIE and prefix map.
     *
     * @param $safeCurie string Safe CURIE.
     *
     * @param $map array|ArrayAccess Map of prefixes to namespace names.
     *
     * @param $defaultPrefixValue string|null Default prefix value to add to
     * unprefixed names.
     */
    public static function newFromSafeCurieAndMap(
        string $safeCurie,
        $map,
        ?string $defaultPrefixValue = null
    ): self {
        if ($safeCurie[0] != '[') {
            throw new SyntaxError(
                $safeCurie,
                0,
                '; safe CURIE must begin with "["'
            );
        }

        if ($safeCurie[-1] != ']') {
            throw new SyntaxError(
                $safeCurie,
                strlen($safeCurie) - 1,
                '; safe CURIE must end with "]"'
            );
        }

        return self::newFromCurieAndMap(
            substr($safeCurie, 1, strlen($safeCurie) - 2),
            $map,
            $defaultPrefixValue
        );
    }

    /**
     * @brief Create from safe CURIE and DOM context node.
     *
     * @param $safeCurie string Safe CURIE.
     *
     * @param $context DOMNode Context node.
     *
     * @param $defaultPrefixValue string|null Default prefix value to add to
     * unprefixed names. If not provided, the context's default namespace is
     * used.
     */
    public static function newFromSafeCurieAndContext(
        string $safeCurie,
        \DOMNode $context,
        ?string $defaultPrefixValue = null
    ): self {
        if ($safeCurie[0] != '[') {
            throw new SyntaxError(
                $safeCurie,
                0,
                '; safe CURIE must begin with "["'
            );
        }

        if ($safeCurie[-1] != ']') {
            throw new SyntaxError(
                $safeCurie,
                strlen($safeCurie) - 1,
                '; safe CURIE must end with "]"'
            );
        }

        return self::newFromCurieAndContext(
            substr($safeCurie, 1, strlen($safeCurie) - 2),
            $context,
            $defaultPrefixValue
        );
    }

    public static function newFromCurieAndMap(
        string $curie,
        $map,
        ?string $defaultPrefixValue = null
    ): self {
        $a = explode(':', $curie, 2);

        if (!isset($a[1]) || $a[0] == '') {
            return new self($defaultPrefixValue . $curie);
        }

        if (!isset($map[$a[0]])) {
            /** @throw UnknownNamespacePrefix if the prefix is not found in
             *  the map. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($map[$a[0]] . $a[1]);
    }

    public static function newFromCurieAndContext(
        string $curie,
        \DOMNode $context,
        ?string $defaultPrefixValue = null
    ): self {
        $a = explode(':', $curie, 2);

        if (!isset($a[1]) || $a[0] == '') {
            return new self(
                ($defaultPrefixValue ?? $context->lookupNamespaceUri(null))
                . $curie
            );
        }

        $nsName = $context->lookupNamespaceURI($a[0]);

        if (!isset($nsName)) {
            /** @throw UnknownNamespacePrefix if the prefix cannot be
             *  resolved. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($nsName . $a[1]);
    }
}
