<?php

namespace alcamo\html_creation\element;

class Meta extends AbstractSpecificElement {
  const TAG_NAME = "meta";

  public function __construct( array $attrs ) {
    parent::__construct( null, $attrs );
  }
}
