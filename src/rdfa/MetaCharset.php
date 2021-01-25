<?php

namespace alcamo\rdfa;

class MetaCharset extends AbstractStmt {
  use LiteralContentTrait;

  const PROPERTY = 'meta:charset';

  public function toHtmlAttrs() : ?array {
    return [ 'charset' => (string)$this ];
  }
}
