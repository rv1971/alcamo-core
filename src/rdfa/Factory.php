<?php

namespace alcamo\rdfa;

use alcamo\object_creation\AbstractStaticNamespaceFactory;

/**
 * @brief Factory that creates RDFa statements from property CURIEs and values
 *
 * @date Last reviewed 2021-06-21
 */
class Factory extends AbstractStaticNamespaceFactory
{
    public const NAMESPACE = __NAMESPACE__;

    /**
     * @copybrief
     * alcamo::object_creation::FactoryInterface::createFromClassName()
     *
     * @return
     * - If $value is an object of the class $className, return it unchanged.
     * - Else if $value is iterable, return an instance of $className taking
     *   the $value items as constructor arguments.
     * - Else if $className has a class constant OBJECT_CLASS, return an
     *   instance of $className taking $value converted to OBJECT_CLASS as
     *   constructor argument.
     * - Else return an instance of $className taking $value as constructor
     * argument.
     */
    public function createFromClassName($className, $value): object
    {
        if ($value instanceof $className) {
            return $value;
        }

        if (is_iterable($value)) {
            return new $className(...$value);
        }

        $objectClass = defined("$className::OBJECT_CLASS")
            ? $className::OBJECT_CLASS
            : null;

        if (isset($objectClass)) {
            if ($value instanceof $objectClass) {
                return new $className($value);
            } elseif (method_exists($objectClass, 'newFromString')) {
                return new $className($objectClass::newFromString($value));
            } else {
                return new $className(new $objectClass($value));
            }
        } else {
            return new $className($value);
        }
    }

    public function createRdfaData(iterable $data): RdfaData
    {
        return RdfaData::newFromIterable($data, $this);
    }
}
