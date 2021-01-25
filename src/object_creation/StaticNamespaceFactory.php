<?php

namespace alcamo\object_creation;

class StaticNamespaceFactory extends AbstractFactory {
  public function name2className( string $name ) : string {
    /** Remove trailing path elements, if any. */
    [ $name ] = explode( '/', $name, 2 );

    /** Split and re-compose with colons and dashes removed and first
     * letters of components uppercased. */
    return static::NAMESPACE . '\\'
      . implode( '', array_map( 'ucfirst', preg_split( '/[-:]/', $name ) ) );
  }
}
