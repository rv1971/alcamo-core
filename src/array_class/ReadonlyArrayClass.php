<?php

namespace alcamo\array_class;

/// Class behaving as an array
class ReadonlyArrayClass implements \Countable, \Iterator, \ArrayAccess {
  use ReadonlyArrayTrait;

  function __construct( array $data = NULL ) {
    $this->data_ = (array)$data;
  }
}
