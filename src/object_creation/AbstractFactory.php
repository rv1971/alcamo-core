<?php

namespace alcamo\object_creation;

/**
 * @brief Factory that creates objects from names
 *
 * @date Last reviewed 2021-06-14
 */
abstract class AbstractFactory implements FactoryInterface
{
    /// @copybrief FactoryInterface::name2className()
    abstract public function name2className(string $name): string;

    /**
     * @copybrief FactoryInterface::createFromClassName()
     *
     * @return
     * - If $value is an object of the class $className, return it unchanged.
     * - Else if $value is iterable, return an instance of $className taking
     *   the $value items as constructor arguments.
     * - Else return an instance of $className taking $value as constructor
     * argument.
     *
     * If you need to create a class whose constructor takes a single iterable
     * parameter, wrap the argument into an array.
     */
    public function createFromClassName($className, $value): object
    {
        if ($value instanceof $className) {
            return $value;
        }

        if (is_iterable($value)) {
            return new $className(...$value);
        }

        return new $className($value);
    }

    /// @copybrief FactoryInterface::createFromName()
    public function createFromName($name, $value): object
    {
        return
        $this->createFromClassName($this->name2className($name), $value);
    }

  /**
   * For each item:
   * - If the value is `null`, skip it.
   * - Else, compute the class name from the key by calling name2className().
   * - If the value is an instance of that class, take it unchanged.
   * - Else if the value is iterable with only one item, create an instance
   *   for that item by calling createFromClassName().
   * - Else if the value is iterable, create an array of instances for the
   *   sub-items by calling createFromClassName() for each of them, indexed by
   *   the string representation of the sub-item.
   * - Else create an instance from the value by calling createFromClassName().
   */
    public function createArray(iterable $data): array
    {
        $result = [];

        foreach ($data as $name => $value) {
            if (!isset($value)) {
                continue;
            }

            $className = $this->name2className($name);

            if ($value instanceof $className) {
                $result[$name] = $value;
            } elseif (is_iterable($value)) {
                $items = [];

                foreach ($value as $valueItem) {
                    $obj = $this->createFromClassName($className, $valueItem);
                    $items[(string)$obj] = $obj;
                }

                $result[$name] = count($items) > 1 ? $items : reset($items);
            } else {
                $result[$name] =
                    $this->createFromClassName($className, $value);
            }
        }

        return $result;
    }
}
