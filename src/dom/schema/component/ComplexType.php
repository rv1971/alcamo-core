<?php

namespace alcamo\dom\schema\component;

class ComplexType extends AbstractType
{
    private $attrs_; ///< Map of XName string to SimpleType or PredefinedType

    public function getAttrs(): array
    {
        if (!isset($this->attrs_)) {
            if ($this->getBaseType()) {
                $this->attrs_ = $this->getBaseType()->getAttrs();
            } else {
                // predefine xsi:type if not inheriting it from base type
                static $xsiTypeName = Schema::XSI_NS . ' type';

                $this->attrs_ =[
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
}
