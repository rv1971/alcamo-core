<?php

namespace alcamo\html_creation\element;

class Thead extends AbstractRowgroupElement
{
    const TAG_NAME = "thead";

    const CELL_CLASS = Th::class; ///< Default class to create cells
}
