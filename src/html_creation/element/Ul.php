<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

class Ul extends AbstractSpecificElement
{
    public const TAG_NAME = "ul";

    /** Wrap each item into an Li if it is not yet an element allowed within
     * TAG_NAME. */
    public function __construct(iterable $items, ?iterable $attrs = null)
    {
        $content = [];

        foreach ($items as $item) {
            $content[] =
                ($item instanceof Raw
                 || $item instanceof Li
                 || $item instanceof AbstractScriptSupportingElement)
                ? $item
                : new Li($item);
        }

        parent::__construct($content, $attrs);
    }
}
