<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;
use alcamo\html_creation\element\{Link, Meta};

abstract class AbstractStmt implements StmtInterface {
  private $object_;
  private $isResource_; /// Whether $object_ is the URL

  public static function getObjectType() : ?string {
    return defined( 'static::OBJECT_CLASS' ) ? static::OBJECT_CLASS : null;
  }

  public function __construct( $object, bool $isResource ) {
    $this->object_ = $object;
    $this->isResource_ = $isResource;
  }

  public function getProperty() {
    return static::PROPERTY;
  }

  public function getObject() {
    return $this->object_;
  }

  public function isResource() : bool {
    return $this->isResource_;
  }

  function __toString() {
    return(string)$this->getObject();
  }

  public function toXmlAttrs() : ?array {
    $attrs = $this->isResource()
      ? [ 'property' => $this->getProperty(), 'resource' => (string)$this ]
      : [ 'property' => $this->getProperty(), 'content' => (string)$this ];

    return $attrs;
  }

  public function toHtmlAttrs() : ?array {
    if ( $this->isResource() ) {
      $rel = $this->getProperty();

      if ( defined( 'static::LINK_REL' ) ) {
        $rel .= ' ' . static::LINK_REL;
      }

      $attrs = [ 'rel' => $rel, 'href' => (string)$this ];
    } else {
      $attrs =
        [ 'property' => $this->getProperty(), 'content' => (string)$this ];

      if ( defined( 'static::META_NAME' ) ) {
        $attrs['name'] = static::META_NAME;
      }
    }

    return $attrs;
  }

  public function toHtmlNodes() : ?Nodes {
    if ( $this->isResource() ) {
      $attrs = $this->toHtmlAttrs();
      return new Nodes( new Link( $attrs['rel'], $attrs['href'], $attrs ) );
    } else {
      return new Nodes( new Meta( $this->toHtmlAttrs() ) );
    }
  }

  public function toHttpHeaders() : ?array {
    return defined( 'static::HTTP_HEADER' )
      ? [ static::HTTP_HEADER => (string)$this ]
      : null;
  }
}
