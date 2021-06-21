<?php

namespace alcamo\rdfa;

/**
 * @brief Object containing an RDFa data object
 *
 * @date Last reviewed 2021-06-21
 */
trait HasRdfaDataTrait
{
    private $rdfaData_;

    public function getRdfaData(): RdfaData
    {
        return $this->rdfaData_;
    }
}
