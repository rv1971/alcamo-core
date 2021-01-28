<?php

namespace alcamo\xml_creation;

/// Raw XML code that is taken as-is within Nodes.
class Raw extends AbstractNode
{
    public function __toString()
    {
        return (string)$this->content_;
    }
}
