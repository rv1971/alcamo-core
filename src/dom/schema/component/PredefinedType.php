<?php

namespace alcamo\dom\schema\component;

use alcamo\xml\XName;

class PredefinedType extends AbstractPredefinedComponent
{
    private $baseComponent_; ///< ?PredefinedTypeComponent

    public function __construct(
        Schema $schema,
        XName $xName,
        ?self $baseComponent = null
    ) {
        parent::__construct($schema, $xName);

        $this->baseComponent = $baseComponent;
    }

    public function getBaseComponent(): ?self
    {
        return $this->baseComponent_;
    }
}
