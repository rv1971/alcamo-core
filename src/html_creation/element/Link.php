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

    /** Determine media type from filename unless `$rel` is `stylesheet` or the
     *  type is set in `$attrs`. */
    if ( $rel != 'stylesheet' && !isset( $attrs['type'] ) ) {
      $attrs =
        [ 'type' => MediaType::newFromFilename( $path ) ] + (array)$attrs;
    }

    return new self( $rel, $href, $attrs );
  }

  public function __construct( $rel, $href, ?array $attrs = null ) {
    parent::__construct( null, compact( 'rel', 'href' ) + (array)$attrs );
  }
}
