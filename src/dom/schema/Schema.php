<?php

namespace alcamo\dom\schema;

use GuzzleHttp\Psr7\UriResolver;
use alcamo\dom\extended\Document;
use alcamo\dom\schema\component\{
    Attr,
    AttrGroup,
    ComplexType,
    Element,
    Group,
    PredefinedType,
    SimpleType
};
use alcamo\dom\xsd\Document as Xsd;
use alcamo\ietf\Uri;

class Schema
{
    public const XSD_NS = Xsd::NS['xsd'];
    public const XSI_NS = Xsd::NS['xsi'];

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

    private $globalAttrs_ = [];      ///< Map of XName string to Attr.
    private $globalAttrGroups_ = []; ///< Map of XName string to AttrGroup.
    private $globalElements_ = [];   ///< Map of XName string to Element.
    private $globalGroups_ = [];     ///< Map of XName string to Group.
    private $globalTypes_ = [];      ///< Map of XName string to AbstractType.

    /// @todo describe type
    private $elementMap_;

    /** @throw AbsoluteUriNeeded when an XSD has a non-absolute URI. */
    private function __construct(iterable $xsds)
    {
        $this->loadXsds();
        $this->initGlobals();
        $this->initElements();
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

    private function loadXsds(iterable $xsds)
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
    }

    private function initGlobals()
    {
        // setup maps of all global definitions
        $globalDefs = [
            'attribute'      => [ Attr::class, &$this->globalAttrs_ ],
            'attributeGroup' => [ AttrGroup::class, &$this->globalAttrGroups_ ],
            'complexType'    => [ ComplexType::class, &$this->globalTypes_ ],
            'element'        => [ Element::class, &$this->globalElements_ ],
            'group'          => [ Group::class, &$this->globalGroups_ ],
            'simpleType'     => [ SimpleType::class, &$this->globalTypes_ ]
        ];

        foreach ($this->xsds_ as $xsd) {
            $targetNs = $xsd->documentElement['targetNamespace'];

            // loop top-level XSD elements having name attributes
            foreach ($xsd->documentElement as $elem) {
                if (isset($elem['name'])) {
                    switch ($elem->localName) {
                        case 'simpleType':
                            $this->globalTypes_ =
                                AbstractSimpleType::newFromSchemaAndXsdElement(
                                    $schema,
                                    $elem
                                );
                            break;

                        default:
                            [ $componentClass, $prop ] =
                                $globalDefs[$elem->localName];

                            $prop[(string)(new XName($targetNs, $elem['name']))] =
                                new $componentClass($schema, $elem);
                    }
                }
            }
        }

        // Add `anyType`.
        $anyType =
            new PredefinedType($this, new XName(self::XSD_NS, 'anyType'));

        $this->globalTypes_[(string)$anyType->getXName()] = $anyType;

        // Add `anySimpleType`.
        $anySimpleType = new PredefinedType(
            $this,
            new XName(self::XSD_NS, 'anySimpleType'),
            $anyType
        );

        $this->globalTypes_[(string)$anySimpleType->getXName()] =
            $anySimpleType;

        // Add `xsi:type` to be `xsd:QName` if undefined.
        $xsiTypeXName = new XName(self::XSI_NS, 'type');

        if (!isset($this->globalAttrs_[(string)$xsiTypeXName])) {
            $this->globalAttrs_[(string)$xsiTypeXName] =
                new PredefinedAttr(
                    $this,
                    $xsiTypeXName
                    $this->globalTypes_[self::XSD_NS . ' QName']
                );
        }
    }

    private function initElements()
    {
    }
}
