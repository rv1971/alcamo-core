<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\extended\Element as ExtElement;
use alcamo\dom\xsd\Element as XsdElement;

class Group extends AbstractXsdComponent
{
    public function lookupElementDecl(ExtElement $element): ?XsdElement
    {
        $elementDecl = $this->xsdElement_
            ->query(".//xsd:element[@name = '$element->localName']");

        if (isset($elementDecl)) {
            return $elementDecl;
        }

        foreach ($this->xsdElement_->query(".//xsd:group") as $group) {
            $elementDecl = $this->schema
                ->globalGroups_[(string)$group['ref']]
                ->lookupElementDecl($element);

            if (isset($elementDecl)) {
                return $elementDecl;
            }
        }

        return null;
    }
}
