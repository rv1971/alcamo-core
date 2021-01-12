<?php

namespace alcamo\array_class;

use alcamo\exception\ReadonlyViolation;

// Prevent writing ArrayAccess to a class property $data_
trait PreventWriteArrayAccessTrait {
  public function offsetSet( $offset, $value ) {
    throw new ReadonlyViolation;
  }

  public function offsetUnset( $offset ) {
    throw new ReadonlyViolation;
  }
}
