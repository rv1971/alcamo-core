<?php

namespace alcamo\html_creation\element;

class A extends AbstractSpecificElement {
  const TAG_NAME = "a";

  public static function newFromUrl(
    $href, $content = null, ?array $attrs = null
  ) : self {
    return
      new self( $content ?? $href, compact( 'href' ) + (array)$attrs );
  }
}
