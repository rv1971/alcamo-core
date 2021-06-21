<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\{A, Link, Meta, Span};
use alcamo\xml_creation\Nodes;

/**
 * @brief RDFa statement where property is a class constant
 *
 * @attention Each derived class must define a class constant PROPERTY_CURIE
 * and ensure that the CURIE's prefix is found in @ref PREFIX_MAP defined or
 * inherited in the class.
 *
 * A derived class may define class constants as follows:
 * - HTTP_HEADER will be used in toHttpHeaders()
 * - LINK_REL will be used as an additional `rel` value in toHtmlAttrs()
 * - META_NAME will be used as the `name` attribute in toHtmlAttrs()
 * - OBJECT_CLASS which will be returned by getObjectClass()
 *
 * @date Last reviewed 2021-06-18
 */
abstract class AbstractStmt implements StmtInterface
{
    /// Prefix map for property CURIEs
    public const PREFIX_MAP = [
        'dc'  => 'http://purl.org/dc/terms/',
        'owl' => 'http://www.w3.org/2002/07/owl#',
        'meta' => 'tag:https://github.com/rv1971/alcamo-core,2021:rdfa:meta#',
        'header' => 'tag:https://github.com/rv1971/alcamo-core,2021:rdfa:header#'
    ];

    private $object_; ///< any type

    private $resourceInfo_; ///< see $resourceInfo parameter of __construct()

    public static function getObjectClass(): ?string
    {
        return defined('static::OBJECT_CLASS') ? static::OBJECT_CLASS : null;
    }

    /**
     * @param $object Object of the RDFa statement.
     *
     * @param $resourceInfo Indicates whether the object is a resource and
     * potentially its label
     * - if `false`: object is not a resource
     * - if `true`: object is a resource without label
     * - else: label of the object resource, any type convertible to string
     */
    public function __construct($object, $resourceInfo)
    {
        $this->object_ = $object;
        $this->resourceInfo_ = $resourceInfo;
    }

    /// @copydoc StmtInterface::getPropertyCurie()
    public function getPropertyCurie(): string
    {
        return static::PROPERTY_CURIE;
    }

    /// @copydoc StmtInterface::getPropertyUri()
    public function getPropertyUri()
    {
        [ $prefix, $reference ] = explode(':', static::PROPERTY_CURIE, 2);

        return static::PREFIX_MAP[$prefix] . $reference;
    }

    /// @copydoc StmtInterface::getPrefixMap()
    public function getPrefixMap(): array
    {
        $prefix = explode(':', static::PROPERTY_CURIE, 2)[0];

        return [ $prefix => static::PREFIX_MAP[$prefix] ];
    }

    /// @copydoc StmtInterface::getObject()
    public function getObject()
    {
        return $this->object_;
    }

    /// @copydoc StmtInterface::isResource()
    public function isResource(): bool
    {
        return (bool)$this->resourceInfo_;
    }

    /// Resource label, if any
    public function getResourceLabel(): ?string
    {
        return is_bool($this->resourceInfo_)
            ? null
            : (string)$this->resourceInfo_;
    }

    /// @copydoc StmtInterface::__toString()
    public function __toString()
    {
        return (string)$this->getObject();
    }

    /// @copydoc StmtInterface::toXmlAttrs()
    public function toXmlAttrs(): ?array
    {
        return [
            'property'
            => static::PROPERTY_CURIE,
            ($this->resourceInfo_ ? 'resource' : 'content')
            => (string)$this
        ];
    }

    /// @copydoc StmtInterface::toHtmlAttrs()
    public function toHtmlAttrs(): ?array
    {
        if ($this->resourceInfo_) {
            $rel = static::PROPERTY_CURIE;

            if (defined('static::LINK_REL')) {
                $rel .= ' ' . static::LINK_REL;
            }

            return [ 'rel' => $rel, 'href' => (string)$this ];
        } else {
            $attrs = [
                'property' => static::PROPERTY_CURIE,
                'content' => (string)$this
            ];

            if (defined('static::META_NAME')) {
                $attrs['name'] = static::META_NAME;
            }
        }

        return $attrs;
    }

    /// @copydoc StmtInterface::toHtmlNodes()
    public function toHtmlNodes(): ?Nodes
    {
        return new Nodes(
            $this->resourceInfo_
            ? new Link(null, $this->toHtmlAttrs())
            : new Meta($this->toHtmlAttrs())
        );
    }

    /**
     * @brief Representation as visible HTML nodes
     *
     * @brief $includeRdfaAttrs Whether to include RDFa attributes. This makes
     * sense if the RDFa data is not contained in the header, in particular if
     * the subject of the RDFa statement is not the entire HTML page but a
     * part of it.
     *
     * While toHtmlNodes() generates HTML code for use in the header, this
     * method generates HTML code for use in the body.
     */
    public function toVisibleHtmlNodes(?bool $includeRdfaAttrs = null): ?Nodes
    {
        if ($this->resourceInfo_) {
            return new Nodes(
                new A(
                    ($this->resourceInfo_ === true
                     ? (string)$this
                     : $this->resourceInfo_),
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
                    [ 'property' => static::PROPERTY_CURIE ]
                )
                : (string)$this
            );
        }
    }

    /// @copydoc StmtInterface::toHttpHeaders()
    public function toHttpHeaders(): ?array
    {
        return defined('static::HTTP_HEADER')
            ? [ static::HTTP_HEADER => [ (string)$this ] ]
            : null;
    }
}
