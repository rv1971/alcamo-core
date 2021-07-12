<?php

namespace alcamo\collection;

/**
 * @namespace alcamo::collection
 *
 * @brief Generic classes and traits that work to some extend like arrays
 */

/**
 * @brief Class that behaves much like an array
 *
 * @date Last reviewed 2021-06-08
 */
class Collection implements \Countable, \Iterator, \ArrayAccess
{
    use CollectionTrait;
}
