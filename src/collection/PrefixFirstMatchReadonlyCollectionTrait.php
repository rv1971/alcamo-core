<?php

namespace alcamo\collection;

/**
 * @brief Like ReadonlyCollectionTrait, but lookup by matching prefix
 */
trait PrefixFirstMatchReadonlyCollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use PrefixFirstMatchReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];

    /// Ensure that $data_ is intitialized with a (potentially empty) array
    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
