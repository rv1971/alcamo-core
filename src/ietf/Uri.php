<?php

namespace alcamo\ietf;

use GuzzleHttp\Psr7\Uri as GuzzleHttpUri;
use alcamo\exception\SyntaxError;
use alcamo\xml\exception\UnknownNamespacePrefix;

/**
 * @namespace alcamo::ietf
 *
 * @brief Classes to model data specified by the ietf
 */

/**
 * @brief Extended URI class with additional factory methods
 *
 * The `Laminas\Diactoros` implementation of Uri is not suitable because it
 * does not support `file:/` URIs.
 *
 * @sa [CURIE Syntax](https://www.w3.org/TR/curie/)
 *
 * @date Last reviewed 2021-06-17
 */
class Uri extends GuzzleHttpUri
{
    /**
     * @brief Create `file:///` URI from local path
     *
     * @param $path Local path.
     *
     * @param $prependScheme Whether to prepend `file:///`.
     *
     * @param $osFamily OS Family, defaults to `PHP_OS_FAMILY`.
     */
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

        /* If $path is absolute and $prependScheme is true, prepend
         * `file:`. Due to the way GuzzleHttp\Psr7\Uri parses and re-assembles
         * URIs, `file:/` becomes `file:///` in __toString. */
        if ($uri[0] == '/' && ($prependScheme ?? true)) {
            $uri = "file:$uri";
        }

        return new self($uri);
    }

    /**
     * @brief Create from CURIE and prefix map.
     *
     * @param $curie CURIE.
     *
     * @param $map array|ArrayAccess Map of CURIE prefixes to namespace names.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names.
     */
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
            /** @throw alcamo::xml::exception::UnknownNamespacePrefix if the
             *  prefix is not found in the map. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($map[$a[0]] . $a[1]);
    }

    /**
     * @brief Create from safe CURIE and prefix map.
     *
     * @param $safeCurie Safe CURIE.
     *
     * @param $map array|ArrayAccess Map of CURIE prefixes to namespace names.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names.
     */
    public static function newFromSafeCurieAndMap(
        string $safeCurie,
        $map,
        ?string $defaultPrefixValue = null
    ): self {
        if ($safeCurie[0] != '[') {
            /** @throw alcamo::exception::SyntaxError if a safe CURIE does not
             *  start with an opening bracket. */
            throw new SyntaxError(
                $safeCurie,
                0,
                '; safe CURIE must begin with "["'
            );
        }

        if ($safeCurie[-1] != ']') {
            /** @throw alcamo::exception::SyntaxError if a safe CURIE does not
             *  end with a closing bracket. */
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
     * @brief Create from URI or safe CURIE and prefix map.
     *
     * @param $uriOrSafeCurie URI or safe CURIE.
     *
     * @param $map array|ArrayAccess Map of CURIE prefixes to values.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names.
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
     * @brief Create from CURIE and DOM context node.
     *
     * @param $curie CURIE.
     *
     * @param $context Context node.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names. If not provided, the context's default namespace is used.
     */
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
            /** @throw alcamo::xml::exception::UnknownNamespacePrefix if the
             *  prefix cannot be resolved. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($nsName . $a[1]);
    }

    /**
     * @brief Create from safe CURIE and DOM context node.
     *
     * @param $safeCurie Safe CURIE.
     *
     * @param $context Context node.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names. If not provided, the context's default namespace is used.
     */
    public static function newFromSafeCurieAndContext(
        string $safeCurie,
        \DOMNode $context,
        ?string $defaultPrefixValue = null
    ): self {
        if ($safeCurie[0] != '[') {
            /** @throw alcamo::exception::SyntaxError if a safe CURIE does not
             *  start with an opening bracket. */
            throw new SyntaxError(
                $safeCurie,
                0,
                '; safe CURIE must begin with "["'
            );
        }

        if ($safeCurie[-1] != ']') {
            /** @throw alcamo::exception::SyntaxError if a safe CURIE does not
             *  end with a closing bracket. */
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

    /**
     * @brief Create from URI or safe CURIE and DOM context node.
     *
     * @param $uriOrSafeCurie URI or safe CURIE.
     *
     * @param $context Context node.
     *
     * @param $defaultPrefixValue Default prefix value to add to unprefixed
     * names. If not provided, the context's default namespace is used.
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
}
