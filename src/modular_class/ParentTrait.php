<?php

namespace alcamo\modular_class;

use alcamo\collection\ReadonlyCollectionTrait;

trait ParentTrait
{
    use ReadonlyCollectionTrait;

    public function addModule($module)
    {
        $module->init($this);
        $this->data_[$module::NAME] = $module;
    }

    public function addModules(iterable $modules)
    {
        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }
}
