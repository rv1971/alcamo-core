<?php

namespace alcamo\collection;

/// Provide array interfaces accessing a class property $data_
trait CollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use ReadArrayAccessTrait;
    use WriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];

    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
