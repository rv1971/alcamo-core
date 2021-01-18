<?php

namespace alcamo\html_creation\element;

class Stylesheet extends Link {
  const REL = 'stylesheet';

  public static function newFromLocalUrl(
    $href, ?array $attrs = null, $path = null
  ) {
    return parent::newFromRelAndLocalUrl( static::REL, $href, $attrs, $path );
  }

  public function __construct( $href, ?array $attrs = null ) {
    return parent::__construct( static::REL, $href, $attrs );
  }
}
