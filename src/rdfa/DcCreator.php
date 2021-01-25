<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Creator;

/**
 * @sa [dc:creator](http://purl.org/dc/terms/creator).
 */
class DcCreator extends AbstractStmt {
  const PROPERTY = 'dc:creator';
  const META_NAME = 'author';
  const LINK_REL = self::META_NAME;
}
