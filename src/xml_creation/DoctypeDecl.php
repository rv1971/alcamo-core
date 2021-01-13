<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/// Doctype declaration
class DoctypeDecl extends AbstractNode {
  protected $name_;
  protected $externalId_;

  function __construct( $name, $externalId = null, $intSubset = null ) {
    if ( !preg_match( self::NAME_REGEXP, $name ) ) {
      /** @throw SyntaxError if $name is not a valid doctype name. */
      throw new SyntaxError( $name, null, '; not a valid XML doctype name' );
    }

    $this->name_ = $name;
    $this->externalId_ = $externalId;

    parent::__construct( $intSubset );
  }

  public function getName() {
    return $this->name_;
  }

  public function getExternalId() {
    return $this->externalId_;
  }

  function __toString() {
    $result = "<!DOCTYPE {$this->name_}";

    if ( isset( $this->externalId_ ) ) {
      $result .= " {$this->externalId_}";
    }

    if ( isset( $this->content_ ) ) {
      $result .= " [ {$this->content_} ]";
    }

    return "$result>";
  }
}
