<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Title;
use alcamo\xml_creation\Nodes;

/**
 * @sa [dc:title](http://purl.org/dc/terms/title).
 */
class DcTitle extends AbstractStmt {
  use LiteralContentTrait;

  const PROPERTY = 'dc:title';

 function toHtmlNodes() : ?Nodes {
   return new Nodes( new Title(
     $this->getObject(), [ 'property' => static::PROPERTY ] ) );
  }
}
