<?php

namespace alcamo\collection;

/// Provide array interfaces accessing a class property $data_
trait CollectionTrait {
  use CountableTrait,
    ArrayIteratorTrait,
    ReadArrayAccessTrait,
    WriteArrayAccessTrait,
    ArrayContainsTrait;

  protected $data_ = [];
}
