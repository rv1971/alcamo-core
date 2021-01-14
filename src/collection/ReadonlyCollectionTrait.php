<?php

namespace alcamo\collection;

/// Provide readonly array interfaces accessing a class property $data_
trait ReadonlyCollectionTrait {
  use CountableTrait;
  use IteratorTrait;
  use ReadArrayAccessTrait;
  use PreventWriteArrayAccessTrait;

  private $data_ = [];
}
