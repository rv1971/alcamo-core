<?php

namespace alcamo\collection;

// Provide contains() accessing a class property $data_ of type array
trait ArrayContainsTrait {
  public function contains( $value ) {
    return in_array( $value, $this->data_, true );
  }
}
