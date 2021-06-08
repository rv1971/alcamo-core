<?php

namespace alcamo\collection;

/**
 * @brief Class that behaves much like a readonly array
 *
 * @date Last reviewed 2021-06-08
 */
class ReadonlyCollection implements \Countable, \Iterator, \ArrayAccess
{
    use ReadonlyCollectionTrait;
}
