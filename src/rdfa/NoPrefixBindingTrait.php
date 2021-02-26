<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/// Do not create prefix bindings from this
trait NoPrefixBindingTrait
{
    public function getPrefixBinding(): array
    {
        return [];
    }
}
