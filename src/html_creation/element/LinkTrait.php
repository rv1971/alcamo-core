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
    $a = explode( '?', $href, 2 );

    if( !isset( $path ) ) {
      $path = $a[0];
    }

    if ( !is_readable( $path ) ) {
      throw new FileNotFound( $path );
    }

    /** Append modification timestamp if not yet present in href. */
    if ( !isset( $a[1] ) ) {
      $href .= '?m=' . gmdate( 'YmdHis', filemtime( $path ) );
    } elseif (
      substr( $a[1], 0, 2 ) != 'm=' && strpos( $a[1], '&m=' ) === false
    ){
      $href .= '&m=' . gmdate( 'YmdHis', filemtime( $path ) );
    }

    return $href;
  }
}
