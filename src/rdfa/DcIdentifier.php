<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Identifier;

/**
 * @sa [dc:identifier](http://purl.org/dc/terms/identifier).
 */
class DcIdentifier extends AbstractStmt {
  use LiteralContentTrait;

  const PROPERTY = 'dc:identifier';
}
