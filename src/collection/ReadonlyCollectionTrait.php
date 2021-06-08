<?php

namespace alcamo\collection;

/**
 * @brief Provide all array-like interfaces in a readonly way, and a property
 * $data_ they refer to.
 *
 * @date Last reviewed 2021-06-08
 */
trait ReadonlyCollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use ReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];

    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
