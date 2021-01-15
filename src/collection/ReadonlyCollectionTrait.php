<?php

namespace alcamo\collection;

/// Provide readonly array interfaces accessing a class property $data_
trait ReadonlyCollectionTrait {
  use CountableTrait,
    ArrayIteratorTrait,
    ReadArrayAccessTrait,
    PreventWriteArrayAccessTrait,
    ArrayContainsTrait;

    protected $data_ = [];
}
