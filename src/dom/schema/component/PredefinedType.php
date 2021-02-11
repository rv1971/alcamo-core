<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\xml\XName;

class PredefinedType extends AbstractPredefinedComponent
{
    private $baseComponent_; ///< ?PredefinedTypeComponent

    public function __construct(
        Schema $schema,
        XName $xName,
        ?AbstractComponent $baseComponent = null
    ) {
        parent::__construct($schema, $xName);

        $this->baseComponent = $baseComponent;
    }

    public function getBaseComponent(): ?self
    {
        return $this->baseComponent_;
    }
}
