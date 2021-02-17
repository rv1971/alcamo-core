<?php

namespace alcamo\dom\extended;

use alcamo\dom\{Attr as BaseAttr, ConverterPool};

class Attr extends BaseAttr
{
    use RegisteredNodeTrait;

    public const XSI_NS = Document::NS['xsi'];

    public const XSI_CONVERTERS = [
        'nil'                       => ConverterPool::class . '::toBool',
        'noNamespaceSchemaLocation' => ConverterPool::class . '::toUri',
        'schemaLocation'            => ConverterPool::class . '::toArray',
        'type'                      => ConverterPool::class . '::toXName'
    ];

    /// To be redefined in child classes with something more sophisticated
    public function getValue()
    {
        if ($this->namespaceURI == self::XSI_NS) {
            $converter = static::XSI_CONVERTERS[$this->localName] ?? null;

            if (isset($converter)) {
                return $converter($this->value, $this);
            }
        }

        return $this->value;
    }
}
