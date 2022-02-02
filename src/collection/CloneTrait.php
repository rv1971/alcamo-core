<?php

namespace alcamo\collection;

/// Clone the $data_ property when cloning the object
trait CloneTrait
{
    public function __clone()
    {
        $this->data_ = clone $this->data_;
    }
}
