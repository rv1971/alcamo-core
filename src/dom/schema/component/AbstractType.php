<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\dom\xsd\Element;

class AbstractType extends AbstractXsdComponent implements TypeInterface
{
    private $baseType_; ///< ?AbstractType

    public function __construct(
        Schema $schema,
        Element $xsdElement,
        $baseType = false
    ) {
        parent::__construct($schema, $xsdElement);

        $this->baseType_ = $baseType;
    }

    public function getBaseType(): ?TypeInterface
    {
        if ($this->baseType === false) {
            $baseXName =
                $this->xsdElement_->query('xsd:*/xsd:*[@base]')[0]['base'];

            $this->baseType_ = isset($baseXName)
                ? $this->schema_->getGlobalType($baseXName)
                : null;
        }

        return $this->baseType_;
    }
}
