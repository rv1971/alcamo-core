<?php

namespace alcamo\html_creation\element;

use alcamo\exception\FileNotFound;

trait LinkTrait {
  /**
   * @param $href string Local URL, potentially with a query part.
   *
   * @return URL enriched with a modification date parameter.
   */
  public static function augmentLocalUrl(
    string $href, ?string &$path = null
  ) : string {
    if( !isset( $path ) ) {
      $path = explode( '?', $href )[0];
    }

    if ( !is_readable( $path ) ) {
      throw new FileNotFound( $path );
    }

    $href .= (strpos( $href, '?' ) === false ? '?' : '&')
      . 'm=' . gmdate( 'YmdHis', filemtime( $path ) );

    return $href;
  }
}
