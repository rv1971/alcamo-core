<?php

namespace alcamo\xml_creation;

use alcamo\collection\Collection;

/// Array of nodes that can be converted to a string
class Nodes extends Collection {
  /**
   * @brief Return xml representation.
   *
   * - Invoke __toString() on NodeInterface objects.
   * - Handle iterables recursively.
   * - Encode any other data with htmlspecialchars().
   */
  static function xmlString( $data ) : string {
    $output = '';

    if ( $data instanceof NodeInterface ) {
      $output .= $data;
    } elseif ( is_iterable( $data ) ) {
      foreach ( $data as $item ) {
        $output .= static::xmlString( $item );
      }
    } else {
      $output .= htmlspecialchars( $data, ENT_NOQUOTES );
    }

    return $output;
  }

  public function __construct( ...$data ) {
    $this->append( $data );
  }

  public function getNodes() : array {
    return $this->data_;
  }

  function __toString() {
    return static::xmlString( $this->data_ );
  }

  /** Build a flat array of nodes. */
  public function append( $data ) {
    if ( $data instanceof NodeInterface ) {
      $this->data_[] = $data;
    } elseif ( is_iterable( $data ) ) {
      foreach ( $data as $item ) {
        $this->append( $item );
      }
    } else {
      $this->data_[] = $data;
    }
  }
}
