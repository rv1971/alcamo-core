<?php

namespace alcamo\html_creation\element;

use alcamo\iana\MediaType;

class Link extends AbstractSpecificElement {
  use LinkTrait;

  const TAG_NAME = "link";

  public static function newFromRelAndLocalUrl(
    $rel, $href, ?array $attrs = null, $path = null
  ) {
    $href = static::augmentLocalUrl( $href, $path );

    if ( $rel != 'stylesheet' ) {
      $attrs =
        [ 'type' => MediaType::newFromFilename( $path ) ] + (array)$attrs;
    }

    return new self( $rel, $href, $attrs );
  }

  public function __construct( $rel, $href, ?array $attrs = null ) {
    parent::__construct( null, compact( 'rel', 'href' ) + (array)$attrs );
  }
}
