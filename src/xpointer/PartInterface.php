<?php

namespace alcamo\xpointer;

interface PartInterface
{
    public function process(
        array &$nsBindings,
        string $data,
        \DOMDocument $doc
    );
}
