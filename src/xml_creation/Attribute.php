<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/// XML attribute
class Attribute extends AbstractNode {
  protected $name_; ///< Attribute name.

  function __construct( $name, $content ) {
    if ( !preg_match( self::NAME_REGEXP, $name ) ) {
      /** @throw SyntaxError if $name is not a valid name. */
      throw new SyntaxError( $name, null, '; not a valid XML attribute name' );
    }

    $this->name_ = $name;

    parent::__construct( $content );
  }

  public function getName() {
    return $this->name_;
  }

  function __toString() {
    if( is_array( $this->content_ ) ) {
      $valueString = implode( ' ', $this->content_ );
    } elseif( is_iterable( $this->content_ ) ) {
      foreach ( $this->content_ as $item ) {
        if ( isset( $valueString ) ) {
          $valueString .= " $item";
        } else {
          $valueString = $item;
        }
      }
    } else {
      $valueString = (string)$this->content_;
    }

    return "{$this->name_}=\"" . htmlspecialchars( $valueString ) . '"';
  }
}
