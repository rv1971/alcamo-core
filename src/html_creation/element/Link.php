<?php

namespace alcamo\html_creation\element;

class Link extends AbstractSpecificElement {
  const TAG_NAME = "link";

  public function __construct( $rel, $href, ?array $attrs = null ) {
    parent::__construct( null, compact( [ 'rel', 'href' ] ) + (array)$attrs );
  }
}
