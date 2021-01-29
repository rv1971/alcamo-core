<?php

namespace alcamo\collection;

/// Class behaving as a readonly array
class Collection implements \Countable, \Iterator, \ArrayAccess
{
    use CollectionTrait;
}
