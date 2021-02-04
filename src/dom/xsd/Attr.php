<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Attr as BaseAttr;

class Attr extends BaseAttr
{
    public const NAME2CONVERTER = [
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
        $name = (string)$this->getXName();

        if (isset(static::NAME2CONVERTER[$name])) {
            $converter = static::NAME2CONVERTER[$name];

            return $this->$converter();
        } else {
            return $this->value;
        }
    }
}
