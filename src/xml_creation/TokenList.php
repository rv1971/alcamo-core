<?php

/**
 * @file
 *
 * @brief Class TokenList.
 */

namespace alcamo\xml_creation;

use Ds\Set;

use alcamo\collection\{CountableTrait,
  DecoratorTrait,
  IteratorAggregateTrait,
  ReadArrayAccessTrait,
  WriteArrayAccessTrait};

/// Set of space-separated tokens similar to DOMTokenList in JavaScript.
class TokenList implements \Countable, \IteratorAggregate, \ArrayAccess {
  use CountableTrait,
    DecoratorTrait,
    IteratorAggregateTrait,
    ReadArrayAccessTrait,
    WriteArrayAccessTrait;

  protected $data_;

  function __construct( $tokens = null ) {
    /** Convert anything that is not iterable to a string and split it at
     * whitespace. */
    if ( !is_iterable( $tokens ) ) {
      $tokens = preg_split( '/\s+/', $tokens );
    }

    $this->data_ = new Set( $tokens );
  }

  public function __toString() {
    return $this->join( ' ' );
  }
}
