<?php

namespace alcamo\rdfa;

trait HasRdfaDataTrait
{
    private $rdfaData_;

    public function getRdfaData(): RdfaData
    {
        return $this->rdfaData_;
    }
}
