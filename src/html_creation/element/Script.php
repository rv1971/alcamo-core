<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class Script extends AbstractSpecificElement {
  const TAG_NAME = "script";

  function __construct( ?iterable $attrs = null, $content = null ) {
    parent::__construct( $attrs, $content ?? '' );
  }
}
