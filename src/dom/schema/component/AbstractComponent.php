<?php

namespace alcamo\dom\schema\component;

use alcamo\xml\{HasXNameInterface, XName};

abstract class AbstractComponent implements HasXNameInterface
{
    protected $schema_; ///< Schema

    public function __construct(Schema $schema)
    {
        $this->schema_ = $schema;
    }

    public function getSchema(): Schema
    {
        return $this->schema_;
    }

    abstract public function getXName(): XName;

    public function __toString()
    {
        return (string)$this->getXName();
    }
}
