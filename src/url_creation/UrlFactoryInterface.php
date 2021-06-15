<?php

namespace alcamo\url_creation;

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
