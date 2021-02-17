<?php

namespace alcamo\dom\psvi;

use alcamo\dom\ConverterPool;
use alcamo\dom\extended\Document as BaseDocument;
use alcamo\dom\schema\{Schema, TypeMap};
use alcamo\dom\schema\component\SimpleTypeInterface;

class Document extends BaseDocument
{
    public const ATTR_TYPE_MAP = [
    self::NS['xh11d'] . ' CURIE'
        => ConverterPool::class . '::curieToUri',

    self::NS['xh11d'] . ' SafeCURIE'
        => ConverterPool::class . '::safeCurieToUri',

    self::NS['xh11d'] . ' URIorSafeCURIE'
        => ConverterPool::class . '::uriOrSafeCurieToUri',

    self::NS['xsd'] . ' anyURI'
        => ConverterPool::class . '::toUri',

    self::NS['xsd'] . ' base64Binary'
        => ConverterPool::class . '::base64ToBinary',

    self::NS['xsd'] . ' boolean'
        => ConverterPool::class . '::toBool',

    self::NS['xsd'] . ' date'
        => ConverterPool::class . '::toDateTime',

    self::NS['xsd'] . ' dateTime'
        => ConverterPool::class . '::toDateTime',

    self::NS['xsd'] . ' decimal'
        => ConverterPool::class . '::toFloat',

    self::NS['xsd'] . ' double'
        => ConverterPool::class . '::toFloat',

    self::NS['xsd'] . ' duration'
        => ConverterPool::class . '::toDuration',

    self::NS['xsd'] . ' float'
        => ConverterPool::class . '::toFloat',

    self::NS['xsd'] . ' hexBinary'
        => ConverterPool::class . '::hexToBinary',

    self::NS['xsd'] . ' integer'
        => ConverterPool::class . '::toInt',

    self::NS['xsd'] . ' language'
        => ConverterPool::class . '::toLang',

    self::NS['xsd'] . ' QName'
        => ConverterPool::class . '::toXName'
    ];

    public const NODE_CLASS =
        [
            'DOMAttr'    => Attr::class,
            'DOMElement' => Element::class
        ]
        + parent::NODE_CLASS;

    private $schema_;         ///< Schema object.
    private $attrConverters_; ///< TypeMap

    public function getSchema(): Schema
    {
        if (!isset($this->schema_)) {
            $this->schema_ = Schema::newFromDocument($this);
        }

        return $this->schema_;
    }

    public function getAttrConverters(): TypeMap
    {
        if (!isset($this->attrConverters_)) {
            $this->attrConverters_ = TypeMap::newFromSchemaAndXNameMap(
                $this->getSchema(),
                static::ATTR_TYPE_MAP
            );
        }

        return $this->attrConverters_;
    }
}
