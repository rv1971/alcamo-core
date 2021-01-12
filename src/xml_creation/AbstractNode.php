<?php

namespace alcamo\xml_creation;

/// Raw XML code that is taken as-is within Nodes.
abstract class AbstractNode implements NodeInterface {
  protected $content_;

  public function __construct( $content = null ) {
    $this->content_ = $content;
  }

  public function getContent() {
    return $this->content_;
  }

  abstract function __toString();
}
