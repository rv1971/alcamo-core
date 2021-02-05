<?php

namespace alcamo\dom\extended;

use alcamo\dom\Attr as BaseAttr;

class Attr extends BaseAttr
{
    use RegisteredNodeTrait;

    public const XSI_NS = Document::NS['xsi'];

    public const XSI_CONVERTERS = [
        'nil'                       => 'toBool',
        'noNamespaceSchemaLocation' => 'toUri',
        'schemaLocation'            => 'toArray',
        'type'                      => 'toXName'
    ];

    /// To be redefined in child classes with something more sophisticated
    public function getValue()
    {
        if ($this->namespaceURI == self::XSI_NS) {
            $converter = static::XSI_CONVERTERS[$this->localName] ?? null;

            if (isset($converter)) {
                return $this->$converter();
            }
        }

        return $this->value;
    }
}
