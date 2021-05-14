<?php

namespace alcamo\html_creation\element;

class Tr extends AbstractSpecificElement
{
    public const TAG_NAME = "tr";

  /** Wrap each item into a Td if it is not yet an element allowed within
   * TAG_NAME. */
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
            $content[] = ($item instanceof Raw
                          || $item instanceof AbstractTableCell
                          || $item instanceof AbstractScriptSupportingElement)
                ? $item
                : new $cellClass($item);
        }

        parent::__construct($content, $attrs);
    }
}
