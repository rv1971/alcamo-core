<?php

namespace alcamo\collection;

/**
 * @brief Like Collection, but lookup by matching prefix
 */
class PrefixFirstMatchCollection implements \Countable, \Iterator, \ArrayAccess
{
    use PrefixFirstMatchCollectionTrait;
}
