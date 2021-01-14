<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Element as XmlElement;
use alcamo\xml_creation\TokenList;

/// HTML element.
class Element extends XmlElement
{
  /// Ensure the `class` attribute is always present and is a TokenList
  public static function sanitizeAttrs( $attrs ) {
    if ( !isset( $attrs['class'] ) ) {
      $attrs['class'] = new TokenList;
    } elseif ( !($attrs['class'] instanceof TokenList) ) {
      $attrs['class'] = new TokenList( $attrs['class'] );
    }

    return $attrs;
  }

  function __construct( $tagName, ?iterable $attrs = null, $content = null ) {
    parent::__construct( $tagName, static::sanitizeAttrs( $attrs ), $content );
  }
}
