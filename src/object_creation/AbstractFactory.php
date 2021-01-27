<?php

namespace alcamo\object_creation;

abstract class AbstractFactory implements FactoryInterface {
  abstract public function name2className( string $name ) : string;

  /**
   * @return
   * - If $value is an object of the class $className, return it unchanged.
   * - Else if $value is iterable, return an instance of $className taking the
   * $value items as constructor arguments.
   */
  public function createFromClassName( $className, $value ) : object {
    if ( $value instanceof $className ) {
      return $value;
    }

    if ( is_iterable( $value ) ) {
      return new $className( ...$value );
    }

    return new $className( $value );
  }

  public function createFromName( $name, $value ) : object {
    return
      $this->createFromClassName( $this->name2className( $name ), $value );
  }

  /**
   * For each item:
   * - Compute the class name from the key.
   * - If the value is an instance of that class, leave it unchanged.
   * - Else if the value is iterable, create an array of instances for the
   *   items.
   * - Else create an instance from that value.
   */
  public function createArray( iterable $data ) : array {
    $result = [];

    foreach ( $data as $name => $value ) {
      $className = $this->name2className( $name );

      if ( $value instanceof $className ) {
        $result[$name] = $value;
      } elseif ( is_iterable( $value ) ) {
        $items = [];

        foreach ( $value as $valueItem ) {
          $items[] = $this->createFromClassName( $className, $valueItem );
        }

        $result[$name] = $items;
      } else {
        $result[$name] = $this->createFromClassName( $className, $value );
      }
    }

    return $result;
  }
}
