<?php

namespace alcamo\collection;

// Provide Countable access to a class property $data_
trait CountableTrait {
  public function count() {
    return count( $this->data_ );
  }
}