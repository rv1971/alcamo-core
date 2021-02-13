<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\dom\xsd\Element;

/// Defintion of an XSD list simple type.
class ListType extends AbstractSimpleType
{
    protected $itemType_; ///< AbstractSimpleType.

    public function __construct(
        Schema $schema,
        Element $xsdElement,
        ?AbstractSimpleType $baseType,
        AbstractSimpleType $itemType
    ) {
        parent::__construct($schema, $xsdElement, $baseType);

        $this->itemType_ = $itemType;
    }

    public function getItemType(): TypeInterface
    {
        return $this->itemType_;
    }
}
