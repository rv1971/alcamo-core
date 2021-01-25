<?php

namespace alcamo\rdfa;

use alcamo\ietf\Lang;

/**
 * @sa [dc:language](http://purl.org/dc/terms/language).
 */
class DcLanguage extends AbstractStmt {
  const PROPERTY     = 'dc:language';
  const HTTP_HEADER  = 'Content-Language';
  const OBJECT_CLASS = Lang::class;

  public function __construct( Lang $lang ) {
    parent::__construct( $lang, false );
  }
}
