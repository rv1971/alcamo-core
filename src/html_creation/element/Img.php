<?php

namespace alcamo\html_creation\element;

use alcamo\exception\FileNotFound;

class Img extends AbstractSpecificElement {
  const TAG_NAME = "img";

  public static function newFromLocalUrl(
    $src, $alt, ?iterable $attrs = null, $path = null ) {
    $attrs = (array)$attrs;

    if( !isset( $path ) ) {
      $path = $src;
    }

    if ( !is_readable( $path ) ) {
      throw new FileNotFound( $path );
    }

    $src .= '?m=' . gmdate( 'YmdHis', filemtime( $path ) );

    list( $width, $height ) = getimagesize( $path );


    return new self( $src, $alt, compact( 'width', 'height' ) + $attrs );
  }

  public function __contruct( $src, $alt, ?iterable $attrs = null ) {
    parent::__construct( null, compact( 'src', 'alt' ) + (array)$attrs );
  }
}
