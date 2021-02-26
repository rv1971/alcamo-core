<?php

namespace alcamo\html_creation;

// Class returning a relative path unchanged
class TrivialRelativeUrlFactory extends AbstractUrlFactory
{
    public function createFromPath(string $path): string
    {
        return DIRECTORY_SEPARATOR == '/'
            ? $path
            : str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}
