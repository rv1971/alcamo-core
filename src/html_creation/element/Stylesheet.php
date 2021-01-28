<?php

namespace alcamo\html_creation\element;

class Stylesheet extends Link
{
    public const REL = 'stylesheet';

    public static function newFromLocalUrl(
        $href,
        ?array $attrs = null,
        $path = null
    ): self {
        $href = static::augmentLocalUrl($href, $path);

        return new self($href, $attrs);
    }

    public function __construct($href, ?array $attrs = null)
    {
        return parent::__construct(static::REL, $href, $attrs);
    }
}
