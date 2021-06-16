<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;
use alcamo\html_creation\element\{A, Link, Meta, Span};

abstract class AbstractStmt implements StmtInterface
{
    public const PREFIX_BINDING = [
        /// Dublin Core namespace
        'dc' => 'http://purl.org/dc/terms/',

        /// OWL namespace
        'owl' => 'http://www.w3.org/2002/07/owl#'
    ];

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

    public function getPropertyCurie(): string
    {
        return static::PROPERTY_CURIE;
    }

    public function getPropertyUri()
    {
        [ $prefix, $reference ] = explode(':', static::PROPERTY_CURIE, 2);

        return static::PREFIX_BINDING[$prefix] . $reference;
    }

    public function getPrefixBinding(): array
    {
        $prefix = explode(':', static::PROPERTY_CURIE, 2)[0];

        return [ $prefix => static::PREFIX_BINDING[$prefix] ];
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
        $attrs = [
            'property' => $this->getPropertyCurie(),
            ($this->isResource() ? 'resource' : 'content') => (string)$this
        ];

        return $attrs;
    }

    public function toHtmlAttrs(): ?array
    {
        if ($this->isResource()) {
            $rel = $this->getPropertyCurie();

            if (defined('static::LINK_REL')) {
                $rel .= ' ' . static::LINK_REL;
            }

            $attrs = [ 'rel' => $rel, 'href' => (string)$this ];
        } else {
            $attrs = [
                'property' => $this->getPropertyCurie(),
                'content' => (string)$this
            ];

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
            return new Nodes(new Link($attrs['href'], $attrs));
        } else {
            return new Nodes(new Meta($this->toHtmlAttrs()));
        }
    }

    public function toVisibleHtmlNodes(?bool $includeRdfaAttrs = null): ?Nodes
    {
        if ($this->isResource()) {
            return new Nodes(
                new A(
                    ($this->resourceLabel_ === true
                     ? (string)$this
                     : $this->resourceLabel_),
                    ($includeRdfaAttrs
                     ? $this->toHtmlAttrs()
                     : [ 'href' => (string)$this ])
                )
            );
        } else {
            return new Nodes(
                $includeRdfaAttrs
                ? new Span(
                    (string)$this,
                    [ 'property' => $this->getPropertyCurie() ]
                )
                : (string)$this
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
