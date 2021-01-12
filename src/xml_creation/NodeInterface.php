<?php

namespace alcamo\xml_creation;

/// Node that can be serialized to XML text
interface NodeInterface {
  /// Node content as given to the constructor, may not be a string.
  public function getContent();

  function __toString();
}
