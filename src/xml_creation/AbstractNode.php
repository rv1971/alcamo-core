<?php

namespace alcamo\xml_creation;

/**
 * @brief XML node that can be serialized to XML text
 *
 * @date Last reviewed 2021-06-15
 */
abstract class AbstractNode implements NodeInterface
{
    protected $content_;

    public function __construct($content = null)
    {
        $this->content_ = $content;
    }

    /// @copydoc NodeInterface::getContent()
    public function getContent()
    {
        return $this->content_;
    }

    /// @copydoc NodeInterface::__toString()
    abstract public function __toString();
}
