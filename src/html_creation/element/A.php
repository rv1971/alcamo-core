<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class A extends AbstractSpecificElement {
  const TAG_NAME = "a";

  public static function newFromUrl(
    $href, $content = null, ?array $attrs = null
  ) : self {
    return
      new self( $content ?? $href, compact( [ 'href' ] ) + (array)$attrs );
  }
}
