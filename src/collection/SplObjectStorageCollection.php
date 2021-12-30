<?php

namespace alcamo\collection;

/**
 * @brief Class wrapping an SplObjectStorage
 */
class SplObjectStorageCollection implements \Countable, \Iterator, \ArrayAccess
{
    use SplObjectStorageCollectionTrait;
}
