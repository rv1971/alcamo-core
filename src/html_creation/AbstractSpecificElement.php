<?php

namespace alcamo\html_creation;

/// Base class for HTML element classes for specific tag names
abstract class AbstractSpecificElement extends Element {
  function __construct( ?iterable $attrs = null, $content = null ) {
    parent::__construct( static::TAG_NAME, $attrs, $content );
  }
}
