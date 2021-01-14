<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class Link extends AbstractSpecificElement {
  const TAG_NAME = "link";

  public function __construct( $rel, $href, ?array $attrs = null ) {
    $attrs = (array)$attrs;

    parent::__construct(
      null,
      [
        'rel' => $rel ?? $attrs['rel'],
        'href' => $href ?? $attrs['href']
      ]
      + $attrs
    );
  }
}
