<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/**
 * @brief RDFa statement
 *
 * @sa [RDFa Primer](https://www.w3.org/TR/rdfa-primer/)
 *
 * @sa [CURIE Syntax](https://www.w3.org/TR/curie/)
 *
 * @date Last reviewed 2021-06-18
 */
interface StmtInterface
{
    /// Class the object must have, or null if there is no constraint
    public static function getObjectClass(): ?string;

    /// Property as a CURIE
    public function getPropertyCurie(): string;

    /// Property as a URI
    public function getPropertyUri();

    /// One-element map of the CURIE prefix to the URI it translates to
    public function getPrefixMap(): array;

    /// Object of the RDFa statement
    public function getObject();

    /// Whether the object is the URI of a resource
    public function isResource(): bool;

    /// String representation of the object
    public function __toString();

    /// Array of attributes needed in XML representation, if any
    public function toXmlAttrs(): ?array;

    /// Array of attributes needed in HTML representation, if any
    public function toHtmlAttrs(): ?array;

    /// HTML representation, if any
    public function toHtmlNodes(): ?Nodes;

    /// Map of HTTP headers to arrays of values, if any
    public function toHttpHeaders(): ?array;
}
