<?php

namespace alcamo\dom;

/// Attribute class for use in DOMDocument::registerNodeClass().
class Attr extends \DOMAttr
{
    public function __toString()
    {
        return $this->value;
    }
}
