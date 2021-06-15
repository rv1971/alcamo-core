<?php

namespace alcamo\xml_creation;

use alcamo\collection\Collection;

/**
 * @brief Array of XML nodes that can be serialized to XML text
 *
 * @date Last reviewed 2021-06-15
 */
class Nodes extends Collection
{
    /**
     * @brief Return serialized XML text
     *
     * - Invoke __toString() on NodeInterface objects.
     * - Handle iterables recursively by calling toXmlString() on each item.
     * - Encode any other data with
     *   [htmlspecialchars()](https://www.php.net/manual/en/function.htmlspecialchars).
     */
    public static function toXmlString($data): string
    {
        $output = '';

        if ($data instanceof NodeInterface) {
            $output .= $data;
        } elseif (is_iterable($data)) {
            foreach ($data as $item) {
                $output .= static::toXmlString($item);
            }
        } else {
            $output .= htmlspecialchars($data, ENT_NOQUOTES);
        }

        return $output;
    }

    public function __construct(...$data)
    {
        $this->append($data);
    }

    public function getNodes(): array
    {
        return $this->data_;
    }

    /// Serialized XML text
    public function __toString()
    {
        return static::toXmlString($this->data_);
    }

    /// Build a flat array of nodes by flattening iterable items
    public function append($data)
    {
        if ($data instanceof NodeInterface) {
            $this->data_[] = $data;
        } elseif (is_iterable($data)) {
            foreach ($data as $item) {
                $this->append($item);
            }
        } else {
            $this->data_[] = $data;
        }
    }
}
