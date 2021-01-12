<?php

namespace alcamo\xml_creation;

/// XML comment.
class Comment implements NodeInterface {
  function __construct( $content ) {
    if ( strpos( $content, '--' ) !== false ) {
      throw new SyntaxError(
        $content, strpos( $content, '--' ), '; double-hyphen in XML comment' );
    }

    parent::__construct( $content );
  }

  function __toString() {
    return "<!-- {$this->content_} -->";
  }
}
