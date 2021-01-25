<?php

namespace alcamo\rdfa;

use alcamo\iana\MediaType;

/**
 * @sa [dc:format](http://purl.org/dc/terms/format).
 */
class DcFormat extends AbstractStmt {
  const PROPERTY     = 'dc:format';
  const HTTP_HEADER  = 'Content-Type';
  const OBJECT_CLASS = MediaType::class;

  public function __construct( MediaType $mediaType ) {
    parent::__construct( $mediaType, false );
  }
}
