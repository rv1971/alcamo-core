<?php

namespace alcamo\dom;

use alcamo\xml\HasXNameInterface;

/// Attribute class for use in DOMDocument::registerNodeClass().
class Attr extends \DOMAttr implements HasXNameInterface
{
    use HasXNameTrait;

    public function __toString()
    {
        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
