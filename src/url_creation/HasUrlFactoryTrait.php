<?php

namespace alcamo\url_creation;

trait HasUrlFactoryTrait
{
    private $urlFactory_;

    public function getUrlFactory(): UrlFactoryInterface
    {
        return $this->urlFactory_;
    }
}
