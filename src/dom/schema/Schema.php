<?php

namespace alcamo\dom\schema;

use GuzzleHttp\Psr7\UriResolver;
use alcamo\dom\extended\Document;
use alcamo\dom\xsd\Document as Xsd;
use alcamo\ietf\Uri;

class Schema
{
    public const XSD_NS = Xsd::NS['xsd'];

    private static $schemaCache_ = [];

    public static function newFromDocument(Document $doc): self
    {
        $urls = [];

        $baseUri = new Uri($doc->baseURI);

        foreach ($doc->documentElement['xsi:schemaLocation'] as $i => $item) {
            if ( $i & 1 ) {
                $urls[] = UriResolver::resolve($baseUri, new Uri($item));
            }
        }

        return self::newFromUrls($urls);
    }

    public static function newFromUrls(array $urls): self
    {
        $cacheKey = implode(' ', $urls);

        if (isset(self::schemaCache_[$cacheKey])) {
            return self::schemaCache_[$cacheKey];
        }

        $xsds = [];

        foreach ($urls as $url) {
            $xds[] = Xsd::newFromUrl($url, true);
        }

        return new self($xsds);
    }

    public static function newFromXsds(iterable $xsds): self
    {
        $urls = [];

        foreach ($xsds as $xsd) {
            $urls[] = $xsd->documentURI;
        }

        $cacheKey = implode(' ', $urls);

        if (isset(self::schemaCache_[$cacheKey])) {
            return self::schemaCache_[$cacheKey];
        }

        return (self::schemaCache_[$cacheKey] = new self($xsds));
    }

    private $xsds_ = [];             ///< Map of URI string to Xsd

    private $globalAttrs_ = [];      ///< Map of XName to Component.
    private $globalAttrGroups_ = []; ///< Map of XName to Component.
    private $globalElements_ = [];   ///< Map of XName to Component.
    private $globalGroups_ = [];     ///< Map of XName to Component.
    private $globalTypes_ = [];      ///< Map of XName to Component.

    /** @throw AbsoluteUriNeeded when an XSD has a non-absolute URI. */
    private function __construct(iterable $xsds)
    {
        // always load XMLSchema.xsd
        $xmlSchemaXsd = Xsd::newFromUrl(
            'file://' . realpath(
                __DIR__ . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . 'xsd' . DIRECTORY_SEPARATOR
                . 'XMLSchema.xsd'
            ),
            true
        );

        $xsds_[$xmlSchemaXsd->documentURI] = $xmlSchemaXsd;

        // load indicated XSDs and XSDs referenced therein
        foreach ($xsds as $xsd) {
            /* Cache all XSDs. addToCache() will throw if documentURI is not
             * absolute. */
            $xsd->addToCache();

            $this->xsds_[$xsd->documentURI] = $xsd;

            // Also load imported XSDs.
            foreach ($xsd->query('xsd:import|xsd:include') as $import) {
                /** Ignore imports without schema location. */
                if (!isset($import['schemaLocation'])) {
                    continue;
                }

                $url = UriResolver::resolve(
                    new Uri($import->baseURI),
                    $import['schemaLocation']
                );

                if (!isset($this->xsds_[$url])) {
                    $this->xsds_[$url] = Xsd::newFromUrl($url, true);
                }
            }
        }

        // setup maps of all global definitions
        $globalDefs = [
            'attribute' => [
                AttrComponent::class,
                &$this->globalAttrs_
            ],
            'attributeGroup' => [
                AttrGroupComponent::class,
                &$this->globalAttrGroups_
            ],
            'complexType' => [
                ComplexTypeComponent::class,
                &$this->globalTypes_
            ],
            'element' => [
                ElementComponent::class,
                &$this->globalElements_
            ],
            'group' => [
                GroupComponent::class,
                &$this->globalGroups_
            ],
            'simpleType' => [
                SimpleTypeComponent::class,
                &$this->globalTypes_
                ]
        ];

        foreach ($this->xsds_ as $xsd) {
            $targetNs = $xsd->documentElement['targetNamespace'];

            // loop top-level XSD elements having name attributes
            foreach ($xsd->documentElement as $elem) {
                if (isset($elem['name'])) {
                    [ $componentClass, $prop ] = $globalDefs[$elem->localName];

                    $prop[(string)(new XName($targetNs, $elem['name']))] =
                        new $componentClass($schema, $elem);
                }
            }
        }

        /** @todo fill types */

        // Add `anyType`.
        $anyType =
            new ComplexType(new XName(self::XSD_NS, 'anyType'));

        $this->globalTypes_[(string)$anyType->getXName()] =
            new PredefinedTypeComponent($this, $anyType);

        // Add `anySimpleType`.
        $anySimpleType =
            new AtomicType(new XName(self::XSD_NS, 'anySimpleType'));

        $this->globalTypes_[(string)$anySimpleType->getXName()] =
            new PredefinedTypeComponent($this, $anySimpleType, null, $anyType);

        // Add `xsi:type` to be `xsd:QName` if undefined.
        $xsiTypeXName = new XName(Xsd::NS['xsi'], 'type');

        if (!isset($this->globalAttrs_[(string)$xsiTypeXName])) {
            $this->globalAttrs_[(string)$xsiTypeXName] =
            new PredefinedComponent(
                $this,
                $this->globalTypes_[self::XSD_NS . ' QName']->getType(),
                $xsiTypeXName
            );
        }
    }

    public function getXsds(): array
    {
        return $this->xsds_;
    }

    public function getGlobalAttrs(): array
    {
        return $this->globalAttrs_;
    }

    public function getGlobalAttrGroups(): array
    {
        return $this->globalAttrGroups_;
    }

    public function getGlobalElements(): array
    {
        return $this->globalElements_;
    }

    public function getGlobalGroups(): array
    {
        return $this->globalGroups_;
    }

    public function getGlobalTypes(): array
    {
        return $this->globalTypes_;
    }
}
