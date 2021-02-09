<?php

namespace alcamo\dom\schema\component;

abstract class AbstractComponent
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
}
