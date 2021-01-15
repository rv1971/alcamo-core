<?php

namespace alcamo\html_creation\element;

class Script extends AbstractScriptSupportingElement {
  const TAG_NAME = "script";

  function __construct( $content = null, ?iterable $attrs = null ) {
    parent::__construct( $content ?? '', $attrs );
  }
}
