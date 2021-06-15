<?php

namespace alcamo\object_creation;

/**
 * @brief Interface for a factory that creates objects from names
 *
 * @date Last reviewed 2021-06-14
 */
interface FactoryInterface
{
    /// Compute a class name from a name
    public function name2className(string $name): string;

    /// Create an object of class $className constructed from $value
    public function createFromClassName($className, $value): object;

    /// Create an object of class name2class($name) constructed from $value
    public function createFromName($name, $value): object;

    /// Create an array from $data, using create() on each item
    public function createArray(iterable $data): array;
}
