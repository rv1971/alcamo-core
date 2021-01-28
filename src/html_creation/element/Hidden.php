<?php

namespace alcamo\html_creation\element;

class Hidden extends Input
{
    function __construct(string $name, $value, ?array $attrs = null)
    {
        parent::__construct('hidden', compact('name', 'value') + (array)$attrs);
    }
}
