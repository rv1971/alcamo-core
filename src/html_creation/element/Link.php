<?php

namespace alcamo\html_creation\element;

use alcamo\iana\MediaType;

/**
 * @brief HTML element \<link>
 *
 * Derived classes my define a class constant REL which becomes the default
 * value for the `rel` attribute.
 *
 * @date Last reviewed 2021-06-16
 */
class Link extends AbstractSpecificElement
{
    use LinkTrait;

    public const TAG_NAME = "link";

    /**
     * @copydetails __construct()
     *
     * @param $path Local path, defaults to $href without query part.
     */
    public static function newFromLocalUrl(
        string $href,
        ?array $attrs = null,
        $path = null
    ): self {
        /** Call LinkTrait::augmentLocalUrl(). */
        $href = static::augmentLocalUrl($href, $path);

        /** Determine media type from filename unless the type is set in
         *  `$attrs`. */
        if (!isset($attrs['type'])) {
            $attrs =
            [ 'type' => MediaType::newFromFilename($path) ] + (array)$attrs;
        }

        return new self($href, $attrs);
    }

    /**
     * @param $href `href` attribute.
     *
     * @param $attrs Further attributes. If `$attrs['rel']` is not set and
     * a class constant REL is defiend, `$attrs['rel']` is set to
     * static::REL. If $href is set, it overrides `$attrs['href']`.
     */
    public function __construct(?string $href, ?array $attrs = null)
    {
        $attrs = (array)$attrs;

        if (!isset($attrs['rel']) && defined('static::REL')) {
            $attrs['rel'] = static::REL;
        }

        if (isset($href)) {
            $attrs['href'] = $href;
        }

        return parent::__construct(null, $attrs);
    }
}
