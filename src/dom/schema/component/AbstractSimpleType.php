<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\schema\Schema;
use alcamo\dom\xsd\Element;

abstract class AbstractSimpleType extends AbstractType
{
    public static function newFromSchemaAndXsdElement(
        Schema $schema,
        Element $xsdElement
    ): self {
        $restrictionElement = $xsdElement->query('xsd:restriction')[0];

        if (isset($restrictionElement)) {
            if (isset($restrictionElement['base'])) {
                $baseType =
                    $schema->globalTypes()[(string)$restrictionElement['base']];
            }

            if ($baseType instanceof ListType) {
                return new ListType(
                    $schema,
                    $xsdElement,
                    $baseType,
                    $baseType->itemType()
                );
            }

            if ($restrictionElement->query('xsd:enumeration')[0]) {
                return new EnumerationType($schema, $xsdElement, $baseType);
            }

            return new AtomicType($schema, $xsdElement, $baseType);
        }

        $listElement = $xsdElement->query('xsd:list')[0];

        if (isset($listElement)) {
            if (isset($listElement['itemType'])) {
                $itemType =
                    $schema->globalTypes()[(string)$listElement['itemType']];
            } else {
                $itemType = self::newFromSchemaAndXsdElement(
                    $schema,
                    $listElement->query('xsd:simpleType')[0]
                );
            }

            return new ListType($schema, $xsdElement, null, $itemType);
        }

        $unionElement = $xsdElement->query('xsd:union')[0];

        if (isset($unionElement)) {
            $memberTypes = [];

            if (isset($unionElement['memberTypes'])) {
                foreach ($unionElement['memberTypes'] as $memberTypeXName) {
                    $memberTypes[] =
                        $schema->globalTypes()[(string)$memberTypeXName];
                }
            }

            foreach (
                $unionElement->query('xsd:simpleType') as $memberTypeElement
            ) {
                $memberTypes[] = self::newFromSchemaAndXsdElement(
                    $schema,
                    $memberTypeElement
                );
            }

            $isEnumeration = true;

            foreach ($memberTypes as $memberType) {
                if (!($memberType instanceof EnumerationTypeInterface)) {
                    $isEnumeration = false;
                    break;
                }
            }

            return $isEnumeration
                ? new EnumerationUnionType($schema, $xsdElement, $memberTypes)
                : new UnionType($schema, $xsdElement, $memberTypes);
        }

        return new AtomicType($schema, $xsdElement);
    }
}