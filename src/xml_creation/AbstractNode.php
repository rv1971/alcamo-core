<?php

namespace alcamo\xml_creation;

/// Raw XML code that is taken as-is within Nodes.
abstract class AbstractNode implements NodeInterface {
  /// Regular expression for XML names
  const NAME_REGEXP =
    '/^[\pL:_][-\pL:.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]*$/u';

  protected $content_;

  public function __construct( $content = null ) {
    $this->content_ = $content;
  }

  public function getContent() {
    return $this->content_;
  }

  abstract function __toString();
}
