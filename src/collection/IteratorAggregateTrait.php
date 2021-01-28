<?php

namespace alcamo\collection;

// Provide IteratorAggregate access to a class property $data_
trait IteratorAggregateTrait
{
    public function getIterator(): \Traversable
    {
        return $this->data_;
    }
}
