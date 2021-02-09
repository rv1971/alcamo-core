<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Attr as BaseAttr;

class Attr extends BaseAttr
{
    public const XSD_NS = Document::NS['xsd'];

    public const XSD_CONVERTERS = [
        'maxOccurs'         => 'toAllNNI',

        'abstract'          => 'toBool',
        'mixed'             => 'toBool',
        'nillable'          => 'toBool',

        'minOccurs'         => 'toInt',

        'namespace'         => 'toUri',
        'schemaLocation'    => 'toUri',
        'source'            => 'toUri',
        'system'            => 'toUri',
        'targetNamespace'   => 'toUri',

        'base'              => 'toXName',
        'itemType'          => 'toXName',
        'ref'               => 'toXName',
        'refer'             => 'toXName',
        'substitutionGroup' => 'toXName',
        'type'              => 'toXName',

        'memberTypes'       => 'toXNames'
    ];

    // Return -1 for `unbounded`.
    public function toAllNNI(): int
    {
        return $this->value == 'unbounded' ? -1 : (int)$this->value;
    }

    public function getValue()
    {
        if (
            $this->parentNode->namespaceURI == self::XSD_NS
            && !isset($this->namespaceURI)
        ) {
            $converter = static::XSD_CONVERTERS[$this->localName] ?? null;

            if (isset($converter)) {
                return $this->$converter();
            }
        }

        return parent::getValue();
    }
}
