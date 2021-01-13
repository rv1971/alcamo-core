<?php

namespace alcamo\array_class;

/// Provide array interfaces accessing a class property $data_
trait ArrayTrait {
  use CountableTrait;
  use IteratorTrait;
  use ReadArrayAccessTrait;
  use WriteArrayAccessTrait;

  private $data_ = [];
}
