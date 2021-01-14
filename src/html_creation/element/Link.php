<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class Link extends AbstractSpecificElement {
  public function __construct( $rel, $href, ?array $attrs = null ) {
    parent::__construct( (array)$attrs + compact( [ 'rel', 'href' ] ) );
  }
}
