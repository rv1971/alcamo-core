<?php

namespace alcamo\html_creation\element;

class Img extends AbstractSpecificElement
{
    use LinkTrait;

    public const TAG_NAME = "img";

    public static function newFromLocalUrl(
        $src,
        $alt,
        ?iterable $attrs = null,
        $path = null
    ) {
        $src = static::augmentLocalUrl($src, $path);

        $a = getimagesize($path);

        if ($a !== false) {
            $attrs = [ 'width' => $a[0], 'height' => $a[1] ] + (array)$attrs;
        }

        return new self($src, $alt, $attrs);
    }

    public function __construct($src, $alt, ?iterable $attrs = null)
    {
        parent::__construct(null, compact('src', 'alt') + (array)$attrs);
    }
}
