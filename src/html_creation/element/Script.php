<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\AbstractSpecificElement;

class Script extends AbstractSpecificElement {
  const TAG_NAME = "script";

  function __construct( $content = null, ?iterable $attrs = null ) {
    parent::__construct( $content ?? '', $attrs );
  }
}
