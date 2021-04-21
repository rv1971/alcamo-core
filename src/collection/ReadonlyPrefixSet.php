<?php

namespace alcamo\collection;

use Ds\Set;

/// Set of prefixes
class ReadonlyPrefixSet implements \Countable, \Iterator, \ArrayAccess
{
    use CountableTrait;
    use ArrayIteratorTrait;
    use ReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;

    protected $data_;
    private $pcre_;   ///< Pcre used by contains().

    /// Create from whitespace-separated list
    public static function newFromString(string $prefixText)
    {
        return new self(new Set(preg_split('/\s+/', $prefixText)));
    }

    /** @warning $prefixes must not contain pipe characters. */
    public function __construct(Set $prefixes)
    {
        $this->data_ = $prefixes;

        $this->pcre_ =
            '~' . str_replace('~', '\~', $prefixes->join('.*|')) . '.*~A';
    }

    public function getPcre(): string
    {
        return $this->pcre_;
    }

    // Whether the set contains an initial substring of $item
    public function contains(string $item): bool
    {
        return preg_match($this->pcre_, $item);
    }
}
