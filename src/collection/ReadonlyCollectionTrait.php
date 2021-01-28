<?php

namespace alcamo\collection;

/// Provide readonly array interfaces accessing a class property $data_
trait ReadonlyCollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use ReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];
}
