<?php

namespace alcamo\dom;

/// Attribute class for use in DOMDocument::registerNodeClass().
class Attr extends \DOMAttr
{
    use HasXNameTrait;

    public function __toString()
    {
        return $this->value;
    }
}
