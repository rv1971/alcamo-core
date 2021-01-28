<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\ConformsTo;

/**
 * @sa [dc:source](http://purl.org/dc/terms/source).
 */
class DcSource extends AbstractStmt {
  const PROPERTY    = 'dc:source';
  const HTTP_HEADER = 'Link';
  const LINK_REL    = 'canonical';

  public function __construct( $source ) {
    parent::__construct( $source, true );
  }

  public function toHttpHeaders() : array {
    return [
      static::HTTP_HEADER => "<{$this}>; rel=\"" . static::LINK_REL . '"'
    ];
  }
}
