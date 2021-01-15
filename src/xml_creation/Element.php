<?php

namespace alcamo\xml_creation;

use alcamo\collection\ReadonlyCollectionTrait;
use alcamo\exception\SyntaxError;

/**
 * @brief XML element
 *
 * Provides array access to attributes.
 */
class Element
  extends AbstractNode
  implements \Countable, \Iterator, \ArrayAccess {
  use ReadonlyCollectionTrait;

  /// Attribute class used for serialization of attributes
  const ATTR_CLASS = Attribute::class;

  protected $tagName_; ///< Tag name

  function __construct(
    string $tagName, ?iterable $attrs = null, $content = null
  ) {
    if ( !preg_match( self::NAME_REGEXP, $tagName ) ) {
      /** @throw SyntaxError if $tagName is not a valid name. */
      throw new SyntaxError( $tagName, null, '; not a valid XML tag name' );
    }

    $this->tagName_ = $tagName;

    if ( isset( $attrs ) ) {
      foreach ( $attrs as $attrName => $attrValue ) {
        if ( !preg_match( self::NAME_REGEXP, $attrName ) ) {
          /** @throw SyntaxError if $attrs contains a invalid attribute name. */
          $e = new SyntaxError(
            $attrName, null, '; not a valid XML attribute name' );

          $e->tagName = $tagName;

          throw $e;
        }

        $this->data_[$attrName] = $attrValue;
      }
    }

    parent::__construct( $content );
  }

  public function getTagName() : string {
    return $this->tagName_;
  }

  public function getAttrs() : array {
    return $this->data_;
  }

  function __toString() {
    $result = "<{$this->tagName_}";

    $attrClass = static::ATTR_CLASS;

    foreach ( $this as $attrName => $attrValue ) {
      $attrString = (string)(new $attrClass( $attrName, $attrValue ));

      if ( $attrString ) {
        $result .= " $attrString";
      }
    }

    if ( isset( $this->content_ ) ) {
      $result .= '>'
        . Nodes::xmlString( $this->content_ )
        . "</{$this->tagName_}>";
    } else {
      $result .= '/>';
    }

    return $result;
  }
}
