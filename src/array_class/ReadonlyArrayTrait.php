<?php

namespace alcamo\array_class;

/// Provide readonly array interfaces accessing a class property $data_
trait ReadonlyArrayTrait {
  use CountableTrait;
  use IteratorTrait;
  use ReadArrayAccessTrait;
  use PreventWriteArrayAccessTrait;

  private $data_ = [];
}
