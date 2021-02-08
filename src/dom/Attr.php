<?php

namespace alcamo\dom;

use alcamo\ietf\Uri;
use alcamo\xml\{HasXNameInterface, XName};

/// Attribute class for use in DOMDocument::registerNodeClass().
class Attr extends \DOMAttr implements HasXNameInterface
{
    use HasXNameTrait;

    public function __toString()
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return preg_split('/\s+/', $this->value);
    }

    public function toBool(): bool
    {
        return $this->value == 'true';
    }

    public function toInt(): int
    {
        return (int)$this->value;
    }

    public function toUri(): Uri
    {
        return new Uri($this->value);
    }

    public function toXName(): XName
    {
        return XName::newFromQNameAndContext($this->value, $this);
    }

    public function toXNames(): array
    {
        $xNames = [];

        foreach (preg_split('/\s+/', $this->value) as $item) {
            $xNames[] = XName::newFromQNameAndContext($item, $this);
        }

        return $xNames;
    }
}
