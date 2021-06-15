<?php

namespace alcamo\xml_creation;

use Ds\Set;
use alcamo\collection\{
  CountableTrait,
  DecoratorTrait,
  IteratorAggregateTrait,
  ReadArrayAccessTrait,
  WriteArrayAccessTrait
};

/**
 * @brief Set of space-separated tokens similar to DOMTokenList in JavaScript
 *
 * @date Last reviewed 2021-06-15
 */
class TokenList implements \Countable, \IteratorAggregate, \ArrayAccess
{
    use CountableTrait;
    use DecoratorTrait;
    use IteratorAggregateTrait;
    use ReadArrayAccessTrait;
    use WriteArrayAccessTrait;

    protected $data_; ///< Set

    public function __construct($tokens = null)
    {
        if (!isset($tokens)) {
            $this->data_ = new Set();
            return;
        } elseif (!is_iterable($tokens)) {
          /** If $tokens is not iterable, convert it to a string and split it
           * at whitespace. */
            $tokens = preg_split('/\s+/', $tokens);
        }

        $this->data_ = new Set($tokens);
    }

    /// Serialize to space-separated list
    public function __toString()
    {
        return $this->join(' ');
    }
}
