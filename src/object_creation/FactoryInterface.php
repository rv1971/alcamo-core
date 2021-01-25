<?php

namespace alcamo\object_creation;

/// Node that can be serialized to XML text
interface FactoryInterface {
  /// Compute a class name from a name
  public function name2className( string $name ) : string;

  /// Create an object of a type $className constructed from $value
  public function createFromClassName( $className, $value ) : object;

    /// Create an object of a type name2class( $name ) constructed from $value
  public function createFromName( $name, $value ) : object;

  /// Create an array from $data, using create() on each item
  public function createArray( iterable $data ) : array;
}
