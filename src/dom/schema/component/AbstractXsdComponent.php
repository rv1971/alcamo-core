<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\dom\xsd\Element;

abstract class AbstractXsdComponent extends AbstractComponent
{
    protected $xsdElement_; ///< Element.

    public function __construct(Schema $schema, Element $xsdElement)
    {
        parent::__construct($schema);

        $this->xsdElement_ = $xsdElement;
    }

    public function getXsdElement(): Element
    {
        return $this->xsdElement_;
    }
}
