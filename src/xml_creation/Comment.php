<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/// XML comment.
class Comment extends AbstractNode {
  function __construct( $content ) {
    if ( strpos( $content, '--' ) !== false ) {
      /** @throw SyntaxError if $content contains double hyphen. */
      throw new SyntaxError(
        $content, strpos( $content, '--' ), '; double-hyphen in XML comment' );
    }

    parent::__construct( $content );
  }

  function __toString() {
    return "<!-- {$this->content_} -->";
  }
}