<?php

namespace alcamo\collection;

use Ds\Set;

/**
 * @brief Set of prefixes
 *
 * @warning The prefixes must not contain pipe characters because contains()
 * is implemented using a PCRE computed in __construct().
 *
 * @date Last reviewed 2021-06-08
 */
class ReadonlyPrefixSet implements \Countable, \IteratorAggregate, \ArrayAccess
{
    use CountableTrait;
    use IteratorAggregateTrait;
    use ReadArrayAccessTrait;
    use PreventWriteArrayAccessTrait;

    protected $data_; ///< Set of prefixes.
    private $pcre_;   ///< Pcre used by contains().

    /// Create from whitespace-separated list of prefixes
    public static function newFromString(string $prefixText): self
    {
        return new self(new Set(preg_split('/\s+/', $prefixText)));
    }

    /**
     * @param $prefixes @copybrief $data_
     *
     * @todo Mask PCRE special characters in $prefixes.
     */
    public function __construct(Set $prefixes)
    {
        $this->data_ = $prefixes;

        $this->pcre_ =
            '~' . str_replace('~', '\~', $prefixes->join('.*|')) . '.*~A';
    }

    /// Return the PCRE used in contains()
    public function getPcre(): string
    {
        return $this->pcre_;
    }

    /// Whether the set contains an initial substring of $value
    public function contains(string $value): bool
    {
        return preg_match($this->pcre_, $value);
    }
}
