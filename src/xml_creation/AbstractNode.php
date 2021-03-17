<?php

namespace alcamo\xml_creation;

abstract class AbstractNode implements NodeInterface
{
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
