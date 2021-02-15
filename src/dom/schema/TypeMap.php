<?php

namespace alcamo\dom\schema;

use alcamo\dom\schema\component\TypeInterface;
use alcamo\exception\Locked;

/**
 * @brief Map whose keys are type hashes.
 *
 * The lookup searches for base types if the type itself has no
 * mapping. The result of the lookup is added to the map to speed up
 * further lookups of the same type.
 */
class TypeMap
{
    private $map_;          ///< Array whose keys are type hashes.
    private $defaultValue_; ///< Default value if no element is found.
    private $isLocked_;     ///< Whether entries have been added to $map_.

    /**
     * @param $map iterable Iterable whose keys are XName strings
     */
    public static function createHashMapFromSchemaAndXNameMap(
        Schema $schema,
        iterable $map
    ) {
        $hashMap = [];

        foreach ($map as $xNameString => $value) {
            $hashMap[spl_object_hash($schema->getGlobalType($xNameString))] =
                $value;
        }

        return $hashMap;
    }

    /**
     * @param $map iterable Iterable whose keys are XName strings
     */
    public static function newFromSchemaAndXNameMap(
        Schema $schema,
        iterable $map,
        $defaultValue = null
    ) {
        return new self(
            self::createHashMapFromSchemaAndXNameMap($schema, $map),
            $defaultValue
        );
    }

    public function __construct(array $map, $defaultValue = null)
    {
        $this->map_ = $map;
        $this->defaultValue_ = $defaultValue;
    }

    public function getMap(): array
    {
        return $this->map_;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue_;
    }

    /// Add elements, not replacing existing ones.
    public function addItems(array $map)
    {
        if ($this->isLocked_) {
            /** @throw Locked when attempting to modify a map where entries
             *  have already been added. */
            throw new Locked($this);
        }

        $this->map_ = $this->map_ + $map;
    }

    /// Add elements, replacing existing ones.
    public function replaceItems(array $map)
    {
        if ($this->isLocked_) {
            /** @throw Locked when attempting to modify a map where entries
             *  have already been added. */
            throw new Locked($this);
        }

        $this->map_ = $map + $this->map_;
    }

    /// Return a value or @ref $defaultValue_.
    public function lookup(TypeInterface $type)
    {
        $hash = spl_object_hash($type);

        // If the type appears in the map, return the value.
        if (isset($this->map_[$hash])) {
            return $this->map_[$hash];
        }

        // Otherwise look for the first matching base type.
        $result = $this->defaultValue_;

        for (
            $type = $type->getBaseType();
            isset($type);
            $type = $type->getBaseType()
        ) {
            $baseHash = spl_object_hash($type);

            if (isset($this->map_[$baseHash])) {
                $result = $this->map_[$baseHash];
                break;
            }
        }

        // Add result of base type lookup to the map.
        $this->map_[$hash] = $result;

        /* Now the map must not be modified any more because a change could
         * invalidate the entry that has been added. */
        $this->isLocked_ = true;

        return $result;
    }
}
