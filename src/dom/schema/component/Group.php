<?php

namespace alcamo\dom\schema\component;

use alcamo\dom\extended\Element as ExtElement;
use alcamo\dom\xsd\{Document as Xsd, Element as XsdElement};

class Group extends AbstractXsdComponent
{
    public const XSD_NS = Xsd::NS['xsd'];

    /**
     * @return array mapping element expanded name string to Element objects
     * for all elements in the content model
     *
     * @warning Content models containing two elements with the same expanded
     * name but different types are not supported.
     */
    public function getElementDecls(): array
    {
        $stack = [ $this->xsdElement_ ];

        $decls = [];

        while ($stack) {
            foreach (array_pop($stack) as $child) {
                if ($child->namespaceURI == self::XSD_NS) {
                    switch ($child->localName) {
                        case 'element':
                            $decl = new Element($this->schema_, $child);

                            $decls[(string)$decl->getXName()] = $decl;

                            break;

                        case 'choice':
                        case 'sequence':
                            $stack[] = $child;
                            break;

                        case 'group':
                            $stack[] = $this->schema_
                                ->getGlobalGroup($child['ref'])->xsdElement_;
                            break;
                    }
                }
            }
        }

        return $decls;
    }

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
