<?php

namespace alcamo\html_creation\element;

class Link extends AbstractSpecificElement {
  const TAG_NAME = "link";

  public static function newFromLocalUrl(
    $rel,  $href, ?array $attrs = null, $path = null
  ) {
    $href = static::augmentLocalUrl( $href, $path );

    return new self( null, compact( 'href' ) + (array)$attrs );
  }

  public function __construct( $rel, $href, ?array $attrs = null ) {
    parent::__construct( null, compact( 'rel', 'href' ) + (array)$attrs );
  }
}
