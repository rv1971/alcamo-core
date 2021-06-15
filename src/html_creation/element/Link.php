<?php

namespace alcamo\html_creation\element;

use alcamo\iana\MediaType;

/**
 * @brief HTML element \<link>
 *
 * @date Last reviewed 2021-06-15
 */
class Link extends AbstractSpecificElement
{
    use LinkTrait;

    public const TAG_NAME = "link";

    /**
     * @param $rel `rel` attribute.
     *
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. $rel and $href override
     * `$attrs['rel']` and `$attrs['href']`.
     *
     * @param $path Local path, defaults to $href without query part.
     */
    public static function newFromRelAndLocalUrl(
        $rel,
        $href,
        ?array $attrs = null,
        $path = null
    ): self {
        /** Call LinkTrait::augmentLocalUrl(). */
        $href = static::augmentLocalUrl($href, $path);

        /** Determine media type from filename unless `$rel` is `stylesheet`
         *  or the type is already set in `$attrs`. */
        if ($rel != 'stylesheet' && !isset($attrs['type'])) {
            $attrs =
            [ 'type' => MediaType::newFromFilename($path) ] + (array)$attrs;
        }

        return new self($rel, $href, $attrs);
    }

    /**
     * @param $rel `rel` attribute.
     *
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. $rel and $href override
     * `$attrs['rel']` and `$attrs['href']`.
     */
    public function __construct($rel, $href, ?array $attrs = null)
    {
        parent::__construct(null, compact('rel', 'href') + (array)$attrs);
    }
}
