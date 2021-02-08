<?php

namespace alcamo\dom\schema\component;

class Element extends AbstractXsdComponent
{
    private $refElement_ = false; ///< ?Element
    private $type_;               ///< AbstractType

    public function getRefElement(): ?self
    {
        if ($this->refElement_ === false) {
            $refXName = $this->xsdElement_['ref'];

            $this->refElement_ = isset($refXName)
                ? $this->schema_->getGlobalElements()[(string)$refXName]
                : null;
        }

        return $this->refElement_;
    }

    public function getType(): AbstractType
    {
        if (!isset($this->type_)) {
            switch (true) {
                case $this->getRefElement():
                    $this->type_ = $this->getRefElement()->getType();
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

                case ($complexTypeElement =
                      $this->xsdElement_->query( 'xsd:complexType' )[0]):
                    $this->type_ =
                        new ComplexType($this->schema_, $complexTypeElement);
                    break;

                default:
                    $this->type_ = $this->schema_->getGlobalTypes()
                        [Schema::XSD_NS . ' anyType'];
            }
        }

        return $this->type_;
    }
}
