<?php

namespace alcamo\html_creation;

trait HasUrlFactoryTrait
{
    private $urlFactory_;

    public function getUrlFactory(): UrlFactoryInterface
    {
        return $this->urlFactory_;
    }
}
