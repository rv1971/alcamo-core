<?php

namespace alcamo\html_creation\element;

use alcamo\exception\FileNotFound;

/**
 * @brief Features for HTML elements that (may) represent a link
 *
 * @date Last reviewed 2021-06-15
 */
trait LinkTrait
{
    /**
     * @param $href string Local URL, potentially with a query part.
     *
     * @param $path Local path, defaults to $href without query part.
     *
     * @return URL enriched with a modification date parameter.
     */
    public static function augmentLocalUrl(
        string $href,
        ?string &$path = null
    ): string {
        $a = explode('?', $href, 2);

        if (!isset($path)) {
            $path = $a[0];
        }

        if (!is_readable($path)) {
            /** @throw alcamo::exception::FileNotFound if $path is not
             *  readable. */
            throw new FileNotFound($path);
        }

        $m = 'm=' . gmdate('YmdHis', filemtime($path));

        /** Append modification timestamp if not yet present in $href. */
        if (!isset($a[1])) {
            $href .= "?$m";
        } elseif (
            substr($a[1], 0, 2) != 'm=' && strpos($a[1], '&m=') === false
        ) {
            $href .= "&$m";
        }

        return $href;
    }
}
