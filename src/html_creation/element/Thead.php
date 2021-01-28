<?php

namespace alcamo\html_creation\element;

class Thead extends AbstractRowgroupElement
{
    public const TAG_NAME = "thead";

    public const CELL_CLASS = Th::class; ///< Default class to create cells
}
