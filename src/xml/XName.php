<?php

namespace alcamo\xml;

use alcamo\xml\exception\UnknownNamespacePrefix;

/**
 * @namespace alcamo::xml
 *
 * @brief Fundamental XML-related classes
 */

/**
 * @brief Expanded name
 *
 * @invariant Immutable class.
 *
 * @sa [Expanded name](https://www.w3.org/TR/xml-names/#dt-expname)
 *
 * @date Last reviewed 2021-06-15
 */
class XName
{
    /**
     * @brief Create from qualified name and namespace map
     *
     * @param $qName qualified name.
     *
     * @param $map array|ArrayAccess map of prefixes to namespace names.
     *
     * @param $defaultNs string|null default namespace to add to unprefixed
     * names.
     */
    public static function newFromQNameAndMap(
        string $qName,
        $map,
        ?string $defaultNs = null
    ): self {
        $a = explode(':', $qName, 2);

        if (!isset($a[1])) {
            return new self($defaultNs, $qName);
        }

        if (!isset($map[$a[0]])) {
            /** @throw alcamo::exception::UnknownNamespacePrefix if the prefix
             *  is not found in the map. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($map[$a[0]], $a[1]);
    }

    /**
     * @brief Create from qualified name and DOM context node
     *
     * @param $qName qualified name
     *
     * @param $context context node
     *
     * @param $defaultNs default namespace to add to unprefixed names; f not
     * provided, the context's default namespace is used
     */
    public static function newFromQNameAndContext(
        string $qName,
        \DOMNode $context,
        ?string $defaultNs = null
    ): self {
        $a = explode(':', $qName, 2);

        if (!isset($a[1])) {
            return new self(
                $defaultNs ?? $context->lookupNamespaceURI(null),
                $qName
            );
        }

        $nsName = $context->lookupNamespaceURI($a[0]);

        if (!isset($nsName)) {
            /** @throw alcamo::exception::UnknownNamespacePrefix if the prefix
             *  cannot be resolved. */
            throw new UnknownNamespacePrefix($a[0]);
        }

        return new self($nsName, $a[1]);
    }

    /**
     * @brief Split a URI into a namespace and a local name
     *
     * This method is designed for cases where a URI is known to be the
     * concatenation of a namespace and an
     * [NCName](https://www.w3.org/TR/xml-names/#NT-NCName).  While in theory
     * the namespace `http://example.com/` and the local name `foo` would be
     * concatenated to the same URI as `http://example.com/f` and `oo`, the
     * latter namespace is very unlikely to be used in practice. Therefore
     * this method splits the URL such that the local name is the longest
     * NCName that can be found at the end of the URI.
     *
     * The method supports the cases where the namespace or the local name or
     * both are the empty string.
     *
     * It is not chekced whether the input is a valid URI.
     */
    public static function newFromUri(
        string $uri,
        ?string $defaultNs = null
    ): self {
        if (
            !preg_match(
                '/^(.*[^\pL_]|)(' . Syntax::NC_NAME . ')$/Uu',
                $uri,
                $matches
            )
        ) {
            return new self($uri != '' ? $uri : (string)$defaultNs, '');
        }

        return new self(
            $matches[1] != '' ? $matches[1] : (string)$defaultNs,
            $matches[2]
        );
    }

    private $nsName_;    ///< Namespace name, if any
    private $localName_; ///< Local name

    /**
     * @warning The syntactic correctness of the arguments is not checked.
     */
    public function __construct(?string $nsName, string $localName)
    {
        $this->nsName_ = $nsName;
        $this->localName_ = $localName;
    }

    public function getNsName(): ?string
    {
        return $this->nsName_;
    }

    public function getLocalName(): string
    {
        return $this->localName_;
    }

    /**
     * @brief Return \<namespace-name>\<space>\<local-name>, or \<local-name>
     * if the namespace is unset.
     *
     * Useful as an array key. Since [Namespaces in XML
     * 1.0](https://www.w3.org/TR/xml-names/) does not define literals for
     * expanded names, any implementation that ensures uniqueness will do. The
     * implementation chosen here is simple to compose and simple to
     * re-convert into an expanded name.
     */
    public function __toString(): string
    {
        return isset($this->nsName_)
            ? "$this->nsName_ $this->localName_"
            : $this->localName_;
    }
}
