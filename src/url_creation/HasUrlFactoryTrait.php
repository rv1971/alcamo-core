<?php

namespace alcamo\url_creation;

/**
 * @brief Object containing a URL factory
 *
 * @date Last reviewed 2021-06-15
 */
trait HasUrlFactoryTrait
{
    private $urlFactory_;

    public function getUrlFactory(): UrlFactoryInterface
    {
        return $this->urlFactory_;
    }
}
