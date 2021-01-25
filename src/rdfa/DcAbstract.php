<?php

namespace alcamo\rdfa;

/**
 * @sa [dc:abstract](http://purl.org/dc/terms/abstract).
 */
class DcAbstract extends AbstractStmt {
  use LiteralContentTrait;

  const PROPERTY = 'dc:abstract';
  const META_NAME = 'description';
}
