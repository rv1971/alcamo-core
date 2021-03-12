<?php

namespace alcamo\conf;

trait HasConfTrait
{
    private $conf_;

    public function getConf()
    {
        return $this->conf_;
    }
}
