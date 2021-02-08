<?php

namespace alcamo\dom\xsd\component;

use alcamo\dom\schema\Schema;
use alcamo\dom\xsd\Element;
use alcamo\xml\XName;

/// Defintion of an XSD list simple type.
class ListType extends AbstractSimpleType
{
    protected $itemType_; ///< AbstractSimpleType.

    function __construct(
        Schema $schema,
        Element $xsdElement,
        AbstractSimpleType $baseType,
        AbstractSimpleType $itemType
    ) {
        parent::__construct($schema, $xsdElement, $baseType);

        $this->itemType_ = $itemType;
    }

    public function getItemType(): AbstractSimpleType
    {
        return $this->itemType_;
    }
}
