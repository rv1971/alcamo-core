<?php

namespace alcamo\modular_class;

trait ModuleTrait
{
    protected $parent_;

    public function init($parent)
    {
        $this->parent_ = $parent;
    }

    public function __call($name, $params)
    {
        return call_user_func_array([ $this->parent_, $name ], $params);
    }
}
