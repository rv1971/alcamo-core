<?php

namespace alcamo\html_creation\element;

use alcamo\iana\MediaType;

/**
 * @brief HTML element \<link> referring to an icon
 *
 * @date Last reviewed 2021-06-16
 */
class Icon extends Link
{
    public const REL = 'icon';

    /// @copydoc Link::newFromLocalUrl()
    public static function newFromLocalUrl(
        string $href,
        ?array $attrs = null,
        $path = null
    ): Link {
        /** Call LinkTrait::augmentLocalUrl(). */
        $href = static::augmentLocalUrl($href, $path);

        /** Determine media type from filename unless the type is set in
         *  `$attrs`. */
        $type =
            isset($attrs['type'])
            ? ($attrs['type'] instanceof MediaType
               ? $attrs['type']
               : MediaType::newFromString($attrs['type']))
            : MediaType::newFromFilename($path);

        if ($type->getType() == 'image') {
            $computedAttrs = [ 'type' => $type ];

            if ($type->getSubtype() == 'svg+xml') {
                $computedAttrs['sizes'] = 'any';
            } else {
                $a = getimagesize($path);

                if ($a !== false) {
                    $computedAttrs['sizes'] = "{$a[0]}x{$a[1]}";
                }
            }

            $attrs = $computedAttrs + (array)$attrs;
        }

        return new self($href, $attrs);
    }
}
