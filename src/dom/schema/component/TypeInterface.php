<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;

interface TypeInterface extends ComponentInterface
{
    public function getBaseType(): ?self;
}
