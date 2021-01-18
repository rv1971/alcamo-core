<?php

namespace alcamo\html_creation\element;

use alcamo\iana\MediaType;

class Icon extends Link {
  const REL = 'icon';

  public static function newFromLocalUrl(
    $href, ?array $attrs = null, $path = null
  ) {
    $href = static::augmentLocalUrl( $href, $path );

    $type = MediaType::newFromFilename( $path );

    if ( $type->getType() == 'image' ) {
      $computedAttrs = [ 'type' => $type ];

      if ( $type->getSubtype() == 'svg+xml' ) {
        $computedAttrs['sizes'] = 'any';
      } else {
        $a = getimagesize( $path );

        if ( $a !== false ) {
          $computedAttrs['sizes'] = "{$a[0]}x{$a[1]}";
        }
      }

      $attrs = $computedAttrs + (array)$attrs;
    }

    return new self( $href, $attrs );
  }

  public function __construct( $href, ?array $attrs = null ) {
    return parent::__construct( $attrs['rel'] ?? static::REL, $href, $attrs );
  }
}
