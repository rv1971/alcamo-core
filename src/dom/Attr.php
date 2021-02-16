<?php

namespace alcamo\dom;

use alcamo\binary_data\BinaryString;
use alcamo\ietf\{Lang, Uri};
use alcamo\time\Duration;
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

    public function toDateTime(): \DateTime
    {
        return new \DateTime($this->value);
    }

    public function toDuration(): Duration
    {
        return new Duration($this->value);
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    /// Convert to integer if value can be represented as int
    public function toInt()
    {
        if (is_int($this->value + 0)) {
            return (int)$this->value;
        } else {
            return $this->value;
        }
    }

    public function toLang(): Lang
    {
        return Lang::newFromString($this->value);
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

    public function base64ToBinary(): BinaryString
    {
        return new BinaryString(base64_decode($this->value));
    }

    public function hexToBinary(): BinaryString
    {
        return new BinaryString(hex2bin($this->value));
    }

    public function curieToUri(): Uri
    {
        return Uri::newFromCurieAndContext($this->value, $this);
    }

    public function safeCurieToUri(): Uri
    {
        return Uri::newFromSafeCurieAndContext($this->value, $this);
    }

    public function uriOrSafeCurieToUri(): Uri
    {
        return Uri::newFromUriOrSafeCurieAndContext($this->value, $this);
    }
}
