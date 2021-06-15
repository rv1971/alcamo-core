<?php

namespace alcamo\html_creation;

use alcamo\xml_creation\Attribute as XmlAttribute;
use alcamo\xml_creation\TokenList;

/**
 * @brief HTML attribute that can be serialized to HTML text
 *
 * @date Last reviewed 2021-06-15
 */
class Attribute extends XmlAttribute
{
    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        if (is_bool($this->content_)) {
          /**
           * If content is boolean:
           * - render `true` as attribute name (e.g. `checked="checked"`)
           * - render `false` as empty string
           */
            return $this->content_ ? "$this->name_=\"$this->name_\"" : '';
        } else {
            return parent::__toString();
        }
    }
}
