<?php

namespace alcamo\collection;

/**
 * @brief Like ReadonlyCollection, but lookup by matching prefix
 */
class PrefixFirstMatchReadonlyCollection implements
    \Countable,
    \Iterator,
    \ArrayAccess
{
    use PrefixFirstMatchReadonlyCollectionTrait;
}
