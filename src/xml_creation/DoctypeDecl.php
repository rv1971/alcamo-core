<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;
use alcamo\xml\Syntax;

/**
 * @brief XML doctype declaration that can be serialized to XML text
 *
 * @sa [XML doctype declarations](https://www.w3.org/TR/xml/#sec-prolog-dtd)
 *
 * @date Last reviewed 2021-06-15
 */
class DoctypeDecl extends AbstractNode
{
    protected $name_; ///< string
    protected $externalId_;

    public function __construct(
        string $name,
        $externalId = null,
        $intSubset = null
    ) {
        if (!preg_match(Syntax::NAME_REGEXP, $name)) {
          /** @throw alcamo::exception::SyntaxError if $name is not a valid
           *  doctype name. */
            throw
                new SyntaxError($name, null, '; not a valid XML doctype name');
        }

        $this->name_ = $name;
        $this->externalId_ = $externalId;

        parent::__construct($intSubset);
    }

    public function getName(): string
    {
        return $this->name_;
    }

    public function getExternalId()
    {
        return $this->externalId_;
    }

    /// @copydoc NodeInterface::__toString()
    public function __toString()
    {
        $result = "<!DOCTYPE $this->name_";

        if (isset($this->externalId_)) {
            $result .= " $this->externalId_";
        }

        if (isset($this->content_)) {
            $result .= " [ $this->content_ ]";
        }

        return "$result>";
    }
}
