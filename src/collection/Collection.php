<?php

namespace alcamo\collection;

/// Class behaving as a read/write array
class Collection implements \Countable, \Iterator, \ArrayAccess
{
    use CollectionTrait;
}
