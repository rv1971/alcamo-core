<?php

namespace alcamo\collection;

/**
 * @brief Readonly class wrapping an SplObjectStorage
 */
class ReadonlySplObjectStorageCollection implements \Countable, \Iterator, \ArrayAccess
{
    use ReadonlySplObjectStorageCollectionTrait;
}
