<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/// XML attribute
class Attribute extends AbstractNode
{
    protected $name_; ///< Attribute name.

    public function __construct(string $name, $content)
    {
        if (!preg_match(self::NAME_REGEXP, $name)) {
            /** @throw SyntaxError if $name is not a valid name. */
            throw new SyntaxError($name, null, '; not a valid XML attribute name');
        }

        $this->name_ = $name;

        parent::__construct($content);
    }

    public function getName(): string
    {
        return $this->name_;
    }

    public function __toString()
    {
        if (is_array($this->content_)) {
            $valueString = implode(' ', $this->content_);
        } elseif (is_iterable($this->content_)) {
            $valueString = '';

            foreach ($this->content_ as $item) {
                if ($valueString) {
                    $valueString .= " $item";
                } else {
                    $valueString = $item;
                }
            }
        } else {
            $valueString = (string)$this->content_;
        }

        /** Return empty string if attribute value is empty */
        return $valueString
            ? "{$this->name_}=\"" . htmlspecialchars($valueString) . '"'
            : '';
    }
}
