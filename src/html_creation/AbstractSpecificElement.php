<?php

namespace alcamo\html_creation;

/// Base class for HTML element classes for specific tag names
abstract class AbstractSpecificElement extends Element {
  function __construct( ?iterable $attrs = null, $content = null ) {
    $tagName = strtolower( substr( strrchr( static::class, '\\' ), 1 ) );

    parent::__construct( $tagName, $attrs, $content );
  }
}
