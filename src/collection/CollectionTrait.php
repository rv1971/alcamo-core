<?php

namespace alcamo\collection;

/// Provide array interfaces accessing a class property $data_
trait CollectionTrait {
  use CountableTrait;
  use IteratorTrait;
  use ReadArrayAccessTrait;
  use WriteArrayAccessTrait;

  private $data_ = [];
}
