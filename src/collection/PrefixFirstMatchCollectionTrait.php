<?php

namespace alcamo\collection;

/**
 * @brief Like CollectionTrait, but lookup by matching prefix
 */
trait PrefixFirstMatchCollectionTrait
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use PrefixFirstMatchReadArrayAccessTrait;
    use WriteArrayAccessTrait;
    use ArrayContainsTrait;

    protected $data_ = [];

    /// Ensure that $data_ is intitialized with a (potentially empty) array
    public function __construct(?array $data = null)
    {
        $this->data_ = (array)$data;
    }
}
