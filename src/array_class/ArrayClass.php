<?php

namespace alcamo\array_class;

/// Class behaving as a readonly array
class ArrayClass implements \Countable, \Iterator, \ArrayAccess {
  use ArrayTrait;

  function __construct( array $data = NULL ) {
    $this->data_ = (array)$data;
  }
}
