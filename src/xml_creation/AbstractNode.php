<?php

namespace alcamo\xml_creation;

abstract class AbstractNode implements NodeInterface
{
    /// Regular expression for XML names
    public const NAME_REGEXP =
    '/^[\pL:_][-\pL:.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]*$/u';

    protected $content_;

    public function __construct($content = null)
    {
        $this->content_ = $content;
    }

    public function getContent()
    {
        return $this->content_;
    }

    abstract public function __toString();
}
