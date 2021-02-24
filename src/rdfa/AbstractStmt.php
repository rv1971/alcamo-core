<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;
use alcamo\html_creation\element\{A, Link, Meta, Span};

abstract class AbstractStmt implements StmtInterface
{
    private $object_;

    /// Label string or `true` if the object is a resource
    private $resourceLabel_;

    public static function getObjectType(): ?string
    {
        return defined('static::OBJECT_CLASS') ? static::OBJECT_CLASS : null;
    }

    public function __construct($object, $resourceLabel)
    {
        $this->object_ = $object;
        $this->resourceLabel_ = $resourceLabel;
    }

    public function getProperty()
    {
        return static::PROPERTY;
    }

    public function getObject()
    {
        return $this->object_;
    }

    public function isResource(): bool
    {
        return (bool)$this->resourceLabel_;
    }

    public function getResourceLabel(): ?string
    {
        switch (true) {
            case $this->resourceLabel_ === true:
                return null;

            case $this->resourceLabel_:
                return $this->resourceLabel_;

            default:
                return null;
        }
    }

    public function __toString()
    {
        return (string)$this->getObject();
    }

    public function toXmlAttrs(): ?array
    {
        $attrs = $this->isResource()
        ? [ 'property' => $this->getProperty(), 'resource' => (string)$this ]
        : [ 'property' => $this->getProperty(), 'content' => (string)$this ];

        return $attrs;
    }

    public function toHtmlAttrs(): ?array
    {
        if ($this->isResource()) {
            $rel = $this->getProperty();

            if (defined('static::LINK_REL')) {
                $rel .= ' ' . static::LINK_REL;
            }

            $attrs = [ 'rel' => $rel, 'href' => (string)$this ];
        } else {
            $attrs =
            [ 'property' => $this->getProperty(), 'content' => (string)$this ];

            if (defined('static::META_NAME')) {
                $attrs['name'] = static::META_NAME;
            }
        }

        return $attrs;
    }

    public function toHtmlNodes(): ?Nodes
    {
        if ($this->isResource()) {
            $attrs = $this->toHtmlAttrs();
            return new Nodes(new Link($attrs['rel'], $attrs['href'], $attrs));
        } else {
            return new Nodes(new Meta($this->toHtmlAttrs()));
        }
    }

    public function toVisibleHtmlNodes(): ?Nodes
    {
        if ($this->isResource()) {
            return new Nodes(
                new A(
                    $this->resourceLabel_ === true
                    ? (string)$this
                    : $this->resourceLabel_,
                    $this->toHtmlAttrs()
                )
            );
        } else {
            return new Nodes(
                new Span((string)$this, [ 'property' => $this->getProperty() ])
            );
        }
    }

    public function toHttpHeaders(): ?array
    {
        return defined('static::HTTP_HEADER')
        ? [ static::HTTP_HEADER => [ (string)$this ] ]
        : null;
    }
}
