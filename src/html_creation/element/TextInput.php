<?php

namespace alcamo\html_creation\element;

class TextInput extends Input
{
    public function __construct(
        ?array $attrs = null
    ) {
        parent::__construct('text', (array)$attrs);
    }
}
