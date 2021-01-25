<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/**
 * @sa [Content-Length](http://tools.ietf.org/html/rfc2616#section-14.13)
 */
class HeaderContentLength extends AbstractStmt {
  use LiteralContentTrait, NoHtmlTrait;

  const PROPERTY    = 'header:content-length';
  const HTTP_HEADER = 'Content-Length';

  public static function newFromFilename( $filename ) {
    return new static( filesize( $filename ) );
  }
}
