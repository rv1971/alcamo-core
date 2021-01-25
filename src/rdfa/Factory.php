<?php

namespace alcamo\rdfa;

use alcamo\object_creation\StaticNamespaceFactory;

class Factory extends StaticNamespaceFactory {
  const NAMESPACE = __NAMESPACE__;

  public function createFromClassName( $className, $value ) : object {
    if ( $value instanceof $className ) {
      return $value;
    }

    if ( is_iterable( $value ) ) {
      return new $className( ...$value );
    }

    $objectClass =
      defined( "$className::OBJECT_CLASS" ) ? $className::OBJECT_CLASS : null;

    if ( isset( $objectClass ) ) {
      if ( $value instanceof $objectClass ) {
        return new $className( $value );
      } elseif ( method_exists( $objectClass, 'newFromString' ) ) {
        return new $className( $objectClass::newFromString( $value ) );
      } else {
        return new $className( new $objectClass( $value ) );
      }
    } else {
      return new $className( $value );
    }
  }

  public function createArray( iterable $data ) : array {
    $result = parent::createArray( $data );

    /** Add `meta:charset` from dc:format if appropriate. */
    if (
      !isset( $result['meta:charset'] )
      && isset( $result['dc:format'] )
      && isset( $result['dc:format']->getObject()->getParams()['charset'] )
    ) {
      $result =
        [
          'meta:charset'
          => new MetaCharset( $result['dc:format']->getObject()->getParams()['charset'] )
        ]
        + $result;
    }

    return $result;
  }
}
