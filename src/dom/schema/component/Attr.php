<?php

namespace alcamo\dom\schema\component;

class Attr extends AbstractXsdComponent
{
    private $refAttr_ = false; ///< ?Attr
    private $type_;            ///< SimpleType

    public function getRefAttr(): ?self
    {
        if ($this->refAttr_ === false) {
            $refXName = $this->xsdAttr_['ref'];

            $this->refAttr_ = isset($refXName)
                ? $this->schema_->getGlobalAttrs()[(string)$refXName]
                : null;
        }

        return $this->refAttr_;
    }

    public function getType(): SimpleType
    {
        if (!isset($this->type_)) {
            switch (true) {
                case $this->getRefAttr():
                    $this->type_ = $this->getRefAttr()->getType();
                    break;

                case isset($this->xsdElement_['type']):
                    $this->type_ = $this->schema_->getGlobalTypes()
                        [(string)$this->xsdElement_['type']];
                    break;

                case ($simpleTypeElement =
                      $this->xsdElement_->query( 'xsd:simpleType' )[0]):
                    $this->type_ =
                        new SimpleType($this->schema_, $simpleTypeElement);
                    break;

                default:
                    $this->type_ = $this->schema_->getGlobalTypes()
                        [Schema::XSD_NS . ' anySimpleType'];
            }
        }

        return $this->type_;
    }
}
