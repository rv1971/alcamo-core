<?php

namespace alcamo\collection;

/**
 * @brief Provide all array-like interfaces and a property $data_ they refer to.
 */
trait SplObjectStorageCollectionTrait
{
    use CountableTrait;
    use SplObjectStorageIteratorTrait;
    use ReadArrayAccessTrait;
    use WriteArrayAccessTrait;
    use ContainsTrait;

    protected $data_;

    /**
     * @brief Ensure that $data_ is intitialized with a (potentially empty)
     * SplObjectStorage
     */
    public function __construct(?\SplObjectStorage $data = null)
    {
        $this->data_ = $data ?? new \SplObjectStorage();
    }
}
