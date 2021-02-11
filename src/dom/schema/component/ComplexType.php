<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\extended\Element as ExtElement;
use alcamo\dom\schema\Schema;

class ComplexType extends AbstractType
{
    private $attrs_; ///< Map of XName string to SimpleType or PredefinedType

    /// Map of element XName string to AbstractType
    private $elementName2Type_;

    public function getAttrs(): array
    {
        if (!isset($this->attrs_)) {
            if ($this->getBaseType()) {
                $this->attrs_ = $this->getBaseType()->getAttrs();
            } else {
                // predefine xsi:type if not inheriting it from base type
                static $xsiTypeName = Schema::XSI_NS . ' type';

                $this->attrs_ = [
                    $xsiTypeName
                    => $this->schema_->getGlobalAttrs()[$xsiTypeName]
                ];
            }

            $complexContent =
                $this->xsdElement_->query('xsd:complexContent')[0];

            $attrParent = isset($complexContent)
                ? $this->xsdElement_->query('xsd:restriction|xsd:extension')[0]
                : $this->xsdElement_;

            foreach ($attrParent as $element) {
                switch ($element->localName) {
                    case 'attribute':
                        $attr = new Attr($this->schema, $element);

                        $this->attrs_[(string)$attr->getXName()] = $attr;

                        break;

                    case 'attributeGroup':
                        $attrGroup = new AttrGroup($this->schema, $element);

                        $this->attrs_ += $attrGroup->getAttrs();
                        break;
                }
            }
        }

        return $this->attrs_;
    }

    /**
     * @warning Cases where a complex type content contains several element
     * declarations with the same name but different type are not supported.
     */
    public function lookupElementType(ExtElement $element): ?AbstractType
    {
        $elementName = (string)$element->getXName();

        if (!array_key_exists($elementName, $this->elementName2Type_)) {
            /* Look for a element with the desired name that belongs to this
             * complex type and not to a nested complex type. */
            foreach (
                $this->xsdElement_->query(
                    ".//xsd:element[@name = '$element->localName']"
                ) as $elementDeclCandidate
            ) {
                if (
                    $this->xsdElement_->isSameNode(
                        $candidate->query('ancestor::xsd:complexType')[0]
                    )
                ) {
                    $elementDecl = $elementDeclCandidate;
                    break;
                }
            }

            // if not found, look up in groups
            if (!isset($elementDecl)) {
                foreach ($this->xsdElement_->query(".//xsd:group") as $group) {
                    if (
                        $this->xsdElement_->isSameNode(
                            $group->query('ancestor::xsd:complexType')[0]
                        )
                    ) {
                        $elementDecl = $this->schema
                            ->globalGroups_[(string)$group['ref']]
                            ->lookupElementDecl($element);

                        if (isset($elementDecl)) {
                            break;
                        }
                    }
                }
            }

            if (isset($elementDecl)) {
                $this->elementName2Type_[$elementName] =
                    (new Element($this->schema, $elementDecl))->getType();
            } else {
                // if not found, look up in parent type, if any
                $this->elementName2Type_[$elementName] =
                    $this->getBaseType()
                    ? $this->getBaseType()->lookupElementType($element)
                    : null;
            }
        }

        return $this->elementName2Type_[$elementName];
    }
}
