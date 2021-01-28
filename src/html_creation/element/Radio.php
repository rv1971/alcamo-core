<?php

namespace alcamo\html_creation\element;

class Radio extends Input
{
    function __construct(
        $name,
        $value,
        $compareTo = null,
        ?array $attrs = null
    ) {
        $attrs = compact('name', 'value') + (array)$attrs;

        if (isset($compareTo)) {
            $attrs['checked'] = $value == $compareTo;
        }

        parent::__construct('radio', $attrs);
    }
}
