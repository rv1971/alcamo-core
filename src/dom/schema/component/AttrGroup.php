<?php

namespace alcamo\dom\schema\component;

class AttrGroup extends AbstractXsdComponent
{
    private $attrs_; ///< Map of XName string to SimpleType or PredefinedType

    public function getAttrs(): array
    {
        if (!isset($this->attrs_)) {
            foreach ($this as $element) {
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
