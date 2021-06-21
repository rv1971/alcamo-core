<?php

namespace alcamo\rdfa;

/**
 * @brief RDFa statement whose property URL is not a public one
 *
 * @date Last reviewed 2021-06-21
 */
trait NoPrefixMapTrait
{
    public function getPrefixMap(): array
    {
        return [];
    }
}
