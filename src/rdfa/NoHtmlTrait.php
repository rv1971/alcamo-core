<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/**
 * @brief RDFa statement without any HTML representation
 *
 * @date Last reviewed 2021-06-21
 */
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
