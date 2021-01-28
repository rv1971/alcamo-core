<?php

namespace alcamo\html_creation\element;

class Script extends AbstractScriptSupportingElement
{
    use LinkTrait;

    public const TAG_NAME = "script";

    public static function newFromLocalUrl(
        $src,
        ?array $attrs = null,
        $path = null
    ): self {
        return new self(
            null,
            [ 'src' => static::augmentLocalUrl($src, $path) ] + (array)$attrs
        );
    }

    public function __construct($content = null, ?iterable $attrs = null)
    {
        parent::__construct($content ?? '', $attrs);
    }
}
