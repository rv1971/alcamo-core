<?php

namespace alcamo\collection;

/// Class behaving as an array
class ReadonlyCollection implements \Countable, \Iterator, \ArrayAccess {
  use ReadonlyCollectionTrait;

  function __construct( ?array $data = null ) {
    $this->data_ = (array)$data;
  }
}
