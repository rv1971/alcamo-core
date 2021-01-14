<?php

namespace alcamo\collection;

/// Class behaving as a readonly array
class Collection implements \Countable, \Iterator, \ArrayAccess {
  use CollectionTrait;

  function __construct( array $data = NULL ) {
    $this->data_ = (array)$data;
  }
}
