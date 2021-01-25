<?php

namespace alcamo\rdfa;

/// Object is always literal content
trait LiteralContentTrait {
  public function __construct( $content ) {
    parent::__construct( $content, false );
  }
}
