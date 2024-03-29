<?php

namespace alcamo\object_creation;

/**
 * @brief Factory that creates objects from local class names
 *
 * @attention Any class derived from this class must define a public constant
 * `NAMESPACE`.
 *
 * @date Last reviewed 2021-06-14
 */
abstract class AbstractStaticNamespaceFactory extends AbstractFactory
{
    /// @copybrief AbstractFactory::name2className()
    public function name2className(string $name): string
    {
        /** Split and re-compose with colons and dashes removed and first
         * letters of components uppercased. Then prepend the constant
         * `NAMESPACE` defined in the derived class */
        return static::NAMESPACE . '\\'
            . str_replace([ '-', ':' ], [], ucwords($name, '-:'));
    }
}
