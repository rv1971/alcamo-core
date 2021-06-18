<?php

namespace alcamo\rdfa;

/**
 * @brief RDFa statement whose object is always a resource
 *
 * @date Last reviewed 2021-06-18
 */
trait ResourceObjectTrait
{
    public function __construct($conformsTo, $resourceLabel = null)
    {
        parent::__construct($conformsTo, $resourceLabel ?? true);
    }
}
