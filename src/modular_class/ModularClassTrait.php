<?php

namespace alcamo\modular_class;

use alcamo\collection\ReadonlyCollectionTrait;

/**
 * @namespace alcamo::modular_class
 *
 * @brief Traits to create modular classes
 */

/**
 * @brief Trait for creating a modular class
 *
 * @attention Any modules added to an object of this class must use
 * ModuleTrait.
 *
 * Modules added to this class can be accessed via the ArrayAccess interface
 * and behave as if they were derived from this class. This is useful if
 * a class would have a very large number of features, and there might even be
 * name clashes between members. Subdividing the functionality into modules
 * helps implementing the single-responsibility principle.
 *
 * @date Last reviewed 2021-06-14
 */
trait ModularClassTrait
{
    use ReadonlyCollectionTrait;

    /// Add a module object
    public function addModule($module): void
    {
        /** Upon addition, call the module's init() method with `$this` as its
         *  argument, this making the parent object known to the module. */
        $module->init($this);
        $this->data_[$module::NAME] = $module;
    }

    public function addModules(iterable $modules): void
    {
        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }
}
