<?php

namespace alcamo\array_class;

// Provide writing ArrayAccess to a class property $data_
trait WriteArrayAccessTrait {
  public function offsetSet( $offset, $value ) {
    $this->data_[$offset] = $value;
  }

  public function offsetUnset( $offset ) {
    unset( $this->data_[$offset] );
  }
}
