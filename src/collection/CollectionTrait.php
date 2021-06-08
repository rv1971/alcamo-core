<?php

namespace alcamo\collection;

/**
 * @brief Provide all array-like interfaces and a property $data_ they refer to.
 *
 * @date Last reviewed 2021-06-08
 */
trait CollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use ReadArrayAccessTrait;
    use WriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];

    /// Ensure that $data_ is intitialized with a (potentially empty) array
    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
