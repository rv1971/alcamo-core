<?php

namespace alcamo\xml_creation;

use alcamo\collection\ReadonlyCollectionTrait;
use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML element that can be serialized to XML text
 *
 * Attributes can be accessed readonly via the Iterator and ArrayAccess
 * interfaces. The Countable interface provides the attribute count.
 *
 * @sa [XML logical structures](https://www.w3.org/TR/xml/#sec-logical-struct)
 *
 * @date Last reviewed 2021-06-15
 */
class Element extends AbstractNode implements
    \Countable,
    \Iterator,
    \ArrayAccess
{
    use ReadonlyCollectionTrait;

    /// Attribute class used for serialization of attributes
    public const ATTR_CLASS = Attribute::class;

    protected $tagName_; ///< string

    public function __construct(
        string $tagName,
        ?iterable $attrs = null,
        $content = null
    ) {
        if (!preg_match(Syntax::NAME_REGEXP, $tagName)) {
            /** @throw alcamo::exception::SyntaxError if $tagName is not a
             *  valid tag name. */
            throw
                new SyntaxError($tagName, null, '; not a valid XML tag name');
        }

        $this->tagName_ = $tagName;

        if (isset($attrs)) {
            foreach ($attrs as $attrName => $attrValue) {
                if (!preg_match(Syntax::NAME_REGEXP, $attrName)) {
                    /** @throw alcamo::exception::SyntaxError if $attrs
                     *  contains a key which is not a valid attribute name. */
                    $e = new SyntaxError(
                        $attrName,
                        null,
                        '; not a valid XML attribute name'
                    );

                    $e->tagName = $tagName;

                    throw $e;
                }

                $this->data_[$attrName] = $attrValue;
            }
        }

        parent::__construct($content);
    }

    public function getTagName(): string
    {
        return $this->tagName_;
    }

    public function getAttrs(): array
    {
        return $this->data_;
    }

    /**
     * @brief Serialize attributes to XML text
     *
     * If the result is nonempty, it starts with a space.
     */
    public function createAttrString(): string
    {
        $result = '';

        $attrClass = static::ATTR_CLASS;

        foreach ($this as $attrName => $attrValue) {
            $attrString = (string)(new $attrClass($attrName, $attrValue));

            if ($attrString) {
                $result .= " $attrString";
            }
        }

        return $result;
    }

    public function createOpeningTag(): string
    {
        return "<{$this->tagName_}{$this->createAttrString()}>";
    }

    public function createClosingTag(): string
    {
        return "</$this->tagName_>";
    }


    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        $result = "<{$this->tagName_}{$this->createAttrString()}";

        if (isset($this->content_)) {
            /** Use Nodes::toXmlString() to serilaize the content. */
            $result .= '>'
                . Nodes::toXmlString($this->content_)
                . "</{$this->tagName_}>";
        } else {
            $result .= '/>';
        }

        return $result;
    }
}
