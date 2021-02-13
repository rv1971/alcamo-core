<?php

namespace alcamo\dom\schema\component;

/// Defintion of an interface for enumeration types
interface EnumerationTypeInterface extends TypeInterface
{
    public function getEnumerators(): array;
}
