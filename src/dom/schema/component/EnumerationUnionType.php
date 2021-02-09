<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\xsd\Enumerator;

/// Defintion of an XSD simple type that is a union of enumerations.
class EnumerationUnionType extends UnionType implements EnumerationTypeInterface
{
    private $enums_; ///< Map of enum strings to Enumerator objects.

    public function getEnums(): array
    {
        if (!isset($this->enums_)) {
            foreach ($this->memberTypes_ as $memberType) {
                $this->enums_ += $memberType->getEnums();
            }

            ksort($this->enums_);
        }

        return $this->enums_;
    }
}
