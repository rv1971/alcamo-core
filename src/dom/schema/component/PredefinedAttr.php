<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\xml\XName;

class PredefinedAttr extends AbstractPredefinedComponent
{
    private $typeComponent_; ///< SimpleTypeComponent

    public function __construct(
        Schema $schema,
        XName $xName,
        AbstractSimpleType $typeComponent
    ) {
        parent::__construct($schema, $xName);

        $this->typeComponent_ = $typeComponent;
    }

    public function getTypeComponent(): SimpleType
    {
        return $this->typeComponent_;
    }
}
