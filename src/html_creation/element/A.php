<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class A extends AbstractSpecificElement {
  const TAG_NAME = "a";

  public static function newFromUrl(
    $href, $content = null, ?array $attrs = null
  ) : self {
    $attrs = (array)$attrs;

    if ( !isset( $href ) ) {
      $href = $attrs['href'];
    }

    return new self(
      $content ?? $href,
      [ 'href' => $href ] + $attrs
    );
  }
}
