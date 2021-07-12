<?php

namespace alcamo\url_creation;

/**
 * @namespace alcamo::url_creation
 *
 * @brief Classes to create URLs for local resources
 */

/**
 * @brief Factory creating URLs for local resources
 *
 * @date Last reviewed 2021-06-15
 */
interface UrlFactoryInterface
{
    /**
     * @param $path local path
     *
     * @return URL
     */
    public function createFromPath(string $path): string;
}
