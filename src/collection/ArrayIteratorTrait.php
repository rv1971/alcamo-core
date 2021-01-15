<?php

namespace alcamo\collection;

// Provide Iterator access to a class property $data_ which is an array
trait ArrayIteratorTrait {
  public function rewind() {
    reset( $this->data_ );
  }

  public function current() {
    return current( $this->data_ );
  }

  public function key() {
    return key( $this->data_ );
  }

  public function next() {
    next( $this->data_ );
  }

  public function valid() {
    return $this->key() !== NULL;
  }

  /// Get the first value.
  public function first() {
    return $this->data_ ? $this->data_[array_key_first( $this->data_ )] : null;
  }

  /// Get the last value.
  public function last() {
    return $this->data_ ? $this->data_[array_key_last( $this->data_ )] : null;
  }
}
