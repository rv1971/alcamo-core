<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML attribute that can be serialized to XML text
 *
 * @sa [XML logical structures](https://www.w3.org/TR/xml/#sec-logical-struct)
 *
 * @date Last reviewed 2021-06-15
 */
class Attribute extends AbstractNode
{
    protected $name_; ///< string

    public function __construct(string $name, $content)
    {
        if (!preg_match(Syntax::NAME_REGEXP, $name)) {
            /** @throw alcamo::exception::SyntaxError if $name is not a valid
             *  name. */
            throw new SyntaxError(
                $name,
                null,
                '; not a valid XML attribute name'
            );
        }

        $this->name_ = $name;

        parent::__construct($content);
    }

    public function getName(): string
    {
        return $this->name_;
    }

    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        /**
         * If the content is an array or iterable, serialize it to a
         * space-separated list.
         */
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

        /** Return empty string if attribute value is empty, thus omitting
         *  attributes with empty value.
         *
         * @warning Hence it is not possible to have attributes with empty
         * value in the serialized XML text, except by overriding this method
         * in a derived class.
         */
        return $valueString
            ? "{$this->name_}=\"" . htmlspecialchars($valueString) . '"'
            : '';
    }
}
