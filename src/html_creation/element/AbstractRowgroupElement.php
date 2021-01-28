<?php

namespace alcamo\html_creation\element;

/// Common base class for Thead, Tbody, Tfoot and Table
abstract class AbstractRowgroupElement extends AbstractSpecificElement
{
    const CELL_CLASS = Td::class; ///< Default class to create cells

  /** Create Tr */
    public static function newFromCellsIterable(
        iterable $items,
        ?iterable $attrs = null
    ): self {
        return new static(new Tr($items, null, static::CELL_CLASS), $attrs);
    }

  /** Wrap each item into a Tr if it is not yet an element allowed within
   * TAG_NAME. */
    public static function newFromRowsIterable(
        iterable $items,
        ?iterable $attrs = null
    ): self {
        $content = [];

        foreach ($items as $item) {
            $content[] = $item instanceof Tr
            ? $item
            : new Tr($item, null, static::CELL_CLASS);
        }

        return new static($content, $attrs);
    }
}
