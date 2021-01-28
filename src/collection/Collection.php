<?php

namespace alcamo\collection;

/// Class behaving as a readonly array
class Collection implements \Countable, \Iterator, \ArrayAccess
{
    use CollectionTrait;

    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
