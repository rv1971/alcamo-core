<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/// Do not create HTML code from this
trait NoHtmlTrait
{
    public function toHtmlNodes(): ?Nodes
    {
        return null;
    }

    public function toVisibleHtmlNodes(?bool $includeRdfaAttrs = null): ?Nodes
    {
        return null;
    }
}
