<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/// RDFa Statement
interface StmtInterface
{
    /// Returns the type the object must have, or null if there is no constraint
    public static function getObjectType(): ?string;

    /// May return a property or an array of properties
    public function getProperty();

    /// May return any type
    public function getObject();

    /// Whether the object is the URI of a resource
    public function isResource(): bool;

    /// String representation of object
    public function __toString();

    /// Returns an array mapping attribute names to values, if any
    public function toXmlAttrs(): ?array;

    /// Returns an HTML-specific array mapping attribute names to values, if any
    public function toHtmlAttrs(): ?array;

    /// Returns an HTML representation, if any
    public function toHtmlNodes(): ?Nodes;

    /// Returns a map of HTTP headers to values, if any
    public function toHttpHeaders(): ?array;
}