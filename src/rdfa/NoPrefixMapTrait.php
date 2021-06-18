<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/// Do not create prefix maps from this
trait NoPrefixMapTrait
{
    public function getPrefixMap(): array
    {
        return [];
    }
}
