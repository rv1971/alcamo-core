<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief Base class for elements that may have \<tr> children
 *
 * @date Last reviewed 2021-06-15
 */
abstract class AbstractRowgroupElement extends AbstractSpecificElement
{
    /// Default class to wrap cell content into
    public const CELL_CLASS = Td::class;

    /**
     * @brief Create an object that contains one Tr item
     *
     * Items are wrapped into @ref CELL_CLASS if needed.
     */
    public static function newFromCellsIterable(
        iterable $items,
        ?iterable $attrs = null
    ): self {
        return new static(new Tr($items, null, static::CELL_CLASS), $attrs);
    }

    /**
     * @brief Create an object that contains an array of Tr items
     *
     * Items are wrapped into Tr elements, wrapping cell content into @ref
     * CELL_CLASS if needed.
     */
    public static function newFromRowsIterable(
        iterable $items,
        ?iterable $attrs = null
    ): self {
        $content = [];

        foreach ($items as $item) {
            $content[] =
                ($item instanceof Raw
                 || $item instanceof Tr
                 || $item instanceof AbstractScriptSupportingElement)
                ? $item
                : new Tr($item, null, static::CELL_CLASS);
        }

        return new static($content, $attrs);
    }
}
