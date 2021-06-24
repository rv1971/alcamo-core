<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Element as XmlElement;
use alcamo\xml_creation\TokenList;

/**
 * @brief HTML element that can be serialized to HTML text
 *
 * @date Last reviewed 2021-06-15
 */
class Element extends XmlElement
{
    /// @copydoc alcamo::xml_creation::Element::ATTR_CLASS
    public const ATTR_CLASS = Attribute::class;

    /// Call XmlElement::__construct/(), then sanitizeAttrs()
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
