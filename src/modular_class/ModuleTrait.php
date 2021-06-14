<?php

namespace alcamo\modular_class;

/**
 * @brief Trait for a class module
 *
 * @attention Any class using this trait MUST define a public constant `NAME`
 * telling the module's name.
 *
 * This trait is a decorator for the parent object.
 *
 * @date Last reviewed 2021-06-14
 */
trait ModuleTrait
{
    protected $parent_; ///< Parent object containing this module

    /// Called by the parent's addModule() method.
    public function init($parent): void
    {
        $this->parent_ = $parent;
    }

    public function __call($name, $params)
    {
        return call_user_func_array([ $this->parent_, $name ], $params);
    }
}
