<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<script>
 *
 * @date Last reviewed 2021-06-16
 */
class Script extends AbstractScriptSupportingElement
{
    use LinkTrait;

    public const TAG_NAME = "script";

    /**
     * @param $src `src` attribute.
     *
     * @param $attrs Further attributes. $src overrides `$attrs['src']`.
     */
    public static function newFromLocalUrl(
        string $src,
        ?array $attrs = null,
        $path = null
    ): self {
        /** Call LinkTrait::augmentLocalUrl(). */
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
