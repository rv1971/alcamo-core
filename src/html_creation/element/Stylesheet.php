<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<link> referring to a stylesheet
 *
 * @date Last reviewed 2021-06-16
 */
class Stylesheet extends Link
{
    public const REL = 'stylesheet'; ///< Fixed value for `rel` attribute

    /// @copydoc Link::newFromLocalUrl()
    public static function newFromLocalUrl(
        string $href,
        ?array $attrs = null,
        $path = null
    ): Link {
        $href = static::augmentLocalUrl($href, $path);

        return new self($href, $attrs);
    }
}
