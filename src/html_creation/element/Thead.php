<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<thead>
 *
 * @date Last reviewed 2021-06-15
 */
class Thead extends AbstractRowgroupElement
{
    public const TAG_NAME = "thead";

    /// @copydoc AbstractRowgroupElement::CELL_CLASS
    public const CELL_CLASS = Th::class;
}
