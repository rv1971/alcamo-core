<?php

namespace alcamo\collection;

/**
 * @brief Provide all array-like interfaces and a property $data_ they refer to.
 *
 * @note Since this trait uses CloneTrait via SplObjectStorageIteratorTrait,
 * write access to a clone through the ArrayAccess mechanism will not modify
 * the data in the original object.
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
