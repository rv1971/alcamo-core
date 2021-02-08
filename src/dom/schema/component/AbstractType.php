<?php

namespace alcamo\dom\schema\component;

class AbstractType extends AbstractXsdComponent
{
    private $baseType_; ///< ?AbstractType

    public function __construct(
        Schema $schema,
        Element $xsdElement,
        ?self $baseType = false
    ) {
        parent::__construct($schema, $xsdElement);

        $this->baseType_ = $baseType;
    }

    public function getBaseType(): ?self
    {
        if ($this->baseType === false) {
            $baseXName =
                $this->xsdElement_->query( 'xsd:*/xsd:*[@base]' )[0]['base'];

            $this->baseType_ = isset($baseXName)
                ? $this->schema_->getGlobalTypes()[(string)$baseXName]
                : null;
        }

        return $this->baseType_;
    }
}
