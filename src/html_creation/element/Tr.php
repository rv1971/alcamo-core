<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief HTML element \<tr>
 *
 * @date Last reviewed 2021-06-15
 */
class Tr extends AbstractSpecificElement
{
    public const TAG_NAME = "tr";

    /**
     * @brief Wrap each item into a cell unless it is an element allowed
     * within \<tr>.
     *
     * @param $cellClass Class to wrap items into, defaults to Td
     */
    public function __construct(
        iterable $items,
        ?iterable $attrs = null,
        string $cellClass = null
    ) {
        if (!isset($cellClass)) {
            $cellClass = Td::class;
        }

        $content = [];

        foreach ($items as $item) {
            $content[] =
                ($item instanceof Raw
                 || $item instanceof AbstractTableCell
                 || $item instanceof AbstractScriptSupportingElement)
                ? $item
                : new $cellClass($item);
        }

        parent::__construct($content, $attrs);
    }
}
