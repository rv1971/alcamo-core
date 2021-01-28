<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Element as XmlElement;
use alcamo\xml_creation\TokenList;

/// HTML element.
class Element extends XmlElement
{
    /// Attribute class used for serialization of attributes
    public const ATTR_CLASS = Attribute::class;

    public function __construct(
        string $tagName,
        ?iterable $attrs = null,
        $content = null
    ) {
        parent::__construct($tagName, $attrs, $content);

        $this->sanitizeAttrs();
    }

    /// Ensure the `class` attribute is always present and is a TokenList
    protected function sanitizeAttrs()
    {
        if (!isset($this->data_['class'])) {
            $this->data_['class'] = new TokenList();
        } elseif (!($this->data_['class'] instanceof TokenList)) {
            $this->data_['class'] = new TokenList($this->data_['class']);
        }
    }
}
