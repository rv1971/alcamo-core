<?php

namespace alcamo\conf;

trait HasConfTrait
{
    private $conf_;

    public function getConf(): array
    {
        return $this->conf_;
    }
}
