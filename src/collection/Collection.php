<?php

namespace alcamo\collection;

/**
 * @brief Class that behaves much like an array
 *
 * @date Last reviewed 2021-06-08
 */
class Collection implements \Countable, \Iterator, \ArrayAccess
{
    use CollectionTrait;
}
