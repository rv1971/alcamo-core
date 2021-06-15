<?php

namespace alcamo\xml;

use alcamo\xml\exception\UnknownNamespacePrefix;

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
    public function __toString()
    {
        return isset($this->nsName_)
            ? "$this->nsName_ $this->localName_"
            : $this->localName_;
    }
}
