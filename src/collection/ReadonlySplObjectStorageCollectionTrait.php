<?php

namespace alcamo\collection;

/**
 * @brief Provide all array-like interfaces and a property $data_ they refer to.
 */
trait ReadonlySplObjectStorageCollectionTrait
{
    use CountableTrait;
    use SplObjectStorageIteratorTrait;
    use ReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;
    use ContainsTrait;

    protected $data_;

    /// Ensure that $data_ is intitialized with a (potentially empty) SplObjectStorage
    public function __construct(?\SplObjectStorage $data = null)
    {
        $this->data_ = $data ?? new \SplObjectStorage();
    }
}
