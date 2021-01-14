<?php

namespace alcamo\collection;

// Provide reading ArrayAccess to a class property $data_
trait ReadArrayAccessTrait {
  public function offsetExists( $offset ) {
    return isset( $this->data_[$offset] );
  }

  public function offsetGet( $offset ) {
    return $this->data_[$offset] ?? null;
  }
}
