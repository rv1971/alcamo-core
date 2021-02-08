<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\xsd\Enumerator;

/// Defintion of an XSD simple type that is an enumeration.
class EnumerationType extends AtomicType implements EnumerationTypeInterface
{
    private $enums_; ///< Map of enum strings to Enumerator objects.

    public function getEnums(): array
    {
        if (!isset($this->enums_)) {
            foreach (
                $this->xsdElement_
                    ->query('xsd:restriction/xsd:enumeration') as $enum
            ) {
                $this->enums_[$enum['value']] = new Enumerator($enum);
            }
    }

        return $this->enums_;
    }
}
