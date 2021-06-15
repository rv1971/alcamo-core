<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<a>
 *
 * @date Last reviewed 2021-06-15
 */
class A extends AbstractSpecificElement
{
    public const TAG_NAME = "a";

    /**
     * @param $href `href` attribute.
     *
     * @param $content Content, defaults to $href.
     *
     * @param $attrs Further attributes. $href overrides `$attrs['href']`.
     */
    public static function newFromUrl(
        $href,
        $content = null,
        ?array $attrs = null
    ): self {
        return
        new self($content ?? $href, compact('href') + (array)$attrs);
    }
}
