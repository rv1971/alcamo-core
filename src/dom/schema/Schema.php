<?php

namespace alcamo\dom\schema;

use GuzzleHttp\Psr7\UriResolver;
use alcamo\dom\extended\{Document, Element as ExtElement};
use alcamo\dom\schema\component\{
    AbstractComponent,
    AbstractSimpleType,
    AbstractType,
    Attr,
    AttrGroup,
    ComplexType,
    Element,
    Group,
    Notation,
    PredefinedAttr,
    PredefinedType,
    SimpleType
};
use alcamo\dom\xsd\{Document as Xsd, Element as XsdElement};
use alcamo\exception\AbsoluteUriNeeded;
use alcamo\ietf\{Uri, UriNormalizer};
use alcamo\xml\XName;

/**
 * @warning `\<redefine>` is not supported.
 */
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
            if ($i & 1) {
                $urls[] = UriResolver::resolve($baseUri, new Uri($item));
            }
        }

        return self::newFromUrls($urls);
    }

    public static function newFromUrls(iterable $urls): self
    {
        $normalizedUrls = [];

        foreach ($urls as $url) {
            $normalizedUrls[] = (string)UriNormalizer::normalize($url);
        }

        $cacheKey = implode(' ', $normalizedUrls);

        if (!isset(self::$schemaCache_[$cacheKey])) {
            $xsds = [];

            foreach ($normalizedUrls as $url) {
                $xsds[] = Xsd::newFromUrl($url, true);
            }

            self::$schemaCache_[$cacheKey] = new self($xsds);
        }

        return self::$schemaCache_[$cacheKey];
    }

    public static function newFromXsds(array $xsds): self
    {
        $urls = [];

        foreach ($xsds as $xsd) {
            $url = new Uri($xsd->documentURI);

            if (!Uri::isAbsolute($url)) {
                /** @throw AbsoluteUriNeeded when attempting to use a
                 * non-absolute URL as a cache key. */
                throw new AbsoluteUriNeeded($xsd->documentURI);
            }

            // normalize URL for use by caching
            $xsd->documentURI = (string)UriNormalizer::normalize($url);

            $urls[] = $xsd->documentURI;
        }

        $cacheKey = implode(' ', $urls);

        if (!isset(self::$schemaCache_[$cacheKey])) {
            self::$schemaCache_[$cacheKey] = new self($xsds);
        }

        return self::$schemaCache_[$cacheKey];
    }

    private $xsds_ = [];             ///< Map of URI string to Xsd

    private $globalAttrs_      = []; ///< Map of XName string to Attr
    private $globalAttrGroups_ = []; ///< Map of XName string to AttrGroup
    private $globalElements_   = []; ///< Map of XName string to Element
    private $globalGroups_     = []; ///< Map of XName string to Group
    private $globalNotations_  = []; ///< Map of XName string to Notation

    ///< Map of XName string to XsdElem AbstractType
    private $globalTypes_ = [];

    private $localComplexTypes_ = []; ///< Map of hash() string to ComplexType

    private $anyType_;                ///< ComplexType
    private $anySimpleType;           ///< PredefinedType

    /** @throw AbsoluteUriNeeded when an XSD has a non-absolute URI. */
    private function __construct(array $xsds)
    {
        $this->loadXsds($xsds);
        $this->initGlobals();
    }

    public function getXsds(): array
    {
        return $this->xsds_;
    }

    public function getGlobalAttr(XName $xName): AbstractComponent
    {
        $key = (string)$xName;

        if ($this->globalAttrs_[$key] instanceof XsdElement) {
            $this->globalAttrs_[$key] =
                new Attr($this, $this->globalAttrs_[$key]);
        }

        return $this->globalAttrs_[$key];
    }

    public function getGlobalAttrGroup(XName $xName): AttrGroup
    {
        $key = (string)$xName;

        if ($this->globalAttrGroups_[$key] instanceof XsdElement) {
            $this->globalAttrGroups_[$key] =
                new AttrGroup($this, $this->globalAttrGroups_[$key]);
        }

        return $this->globalAttrGroups_[$key];
    }

    public function getGlobalElement(XName $xName): Element
    {
        $key = (string)$xName;

        if ($this->globalElements_[$key] instanceof XsdElement) {
            $this->globalElements_[$key] =
                new Element($this, $this->globalElements_[$key]);
        }

        return $this->globalElements_[$key];
    }

    public function getGlobalGroup(XName $xName): Group
    {
        $key = (string)$xName;

        if ($this->globalGroups_[$key] instanceof XsdElement) {
            $this->globalGroups_[$key] =
                new Group($this, $this->globalGroups_[$key]);
        }

        return $this->globalGroups_[$key];
    }

    public function getGlobalNotation(XName $xName): Notation
    {
        $key = (string)$xName;

        if ($this->globalNotations_[$key] instanceof XsdElement) {
            $this->globalNotations_[$key] =
                new Notation($this, $this->globalNotations_[$key]);
        }

        return $this->globalNotations_[$key];
    }

    public function getGlobalType(XName $xName): AbstractComponent
    {
        $key = (string)$xName;

        if (!isset($this->globalTypes_[$key])) {
            var_dump(array_keys($this->globalTypes_));
        }

        $comp = $this->globalTypes_[$key];

        if ($comp instanceof XsdElement) {
            $this->globalTypes_[$key] =
                $comp->localName == 'simpleType'
                ? AbstractSimpleType::newFromSchemaAndXsdElement($this, $comp)
                : new ComplexType($this, $comp);
        }

        return $this->globalTypes_[$key];
    }

    public function getAnyType(): ComplexType
    {
        return $this->anyType_;
    }

    public function getAnySimpleType(): PredefinedType
    {
        return $this->anySimpleType_;
    }

    public function lookupElementType(ExtElement $element): ?AbstractType
    {
        // lookup global type if explicitely given in `xsi:type`
        if (isset($element['xsi:type'])) {
            return $this->getGlobalType($element['xsi:type']);
        }

        // lookup global element, if there is one
        $elementName = (string)$element->getXName();

        if (isset($this->globalElements_[$elementName])) {
            return $this->globalElements_[$elementName]->getType();
        }

        // go recursively via parent element
        return $this->lookupType($element->parentNode)
            ->lookupElementType($element);
    }

    private function loadXsds(array $xsds)
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

        $xsds[] = $xmlSchemaXsd;

        // load indicated XSDs and XSDs referenced therein
        while ($xsds) {
            $xsd = array_shift($xsds);

            if (!isset($this->xsds_[$xsd->documentURI])) {
                $this->xsds_[$xsd->documentURI] = $xsd;

                /* Cache all XSDs. addToCache() will throw if documentURI is
                 * not absolute. */
                $xsd->addToCache();

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

                    if (!isset($this->xsds_[(string)$url])) {
                        $xsds[] = Xsd::newFromUrl($url, true);
                    }
                }
            }
        }
    }

    private function initGlobals()
    {
        // setup maps of all global definitions
        $globalDefs = [
            'attribute'      => &$this->globalAttrs_,
            'attributeGroup' => &$this->globalAttrGroups_,
            'complexType'    => &$this->globalTypes_,
            'element'        => &$this->globalElements_,
            'group'          => &$this->globalGroups_,
            'notation'       => &$this->globalNotations_,
            'simpleType'     => &$this->globalTypes_
        ];

        foreach ($this->xsds_ as $xsd) {
            $targetNs = $xsd->documentElement['targetNamespace'];

            // loop top-level XSD elements having name attributes
            foreach ($xsd->documentElement as $elem) {
                if (isset($elem['name'])) {
                    $globalDefs[$elem->localName]
                        [(string)(new XName($targetNs, $elem['name']))]
                        = $elem;
                }
            }
        }

        $this->anyType_ =
            $this->getGlobalType(new XName(self::XSD_NS, 'anyType'));

        // Add `anySimpleType`.
        $anySimpleTypeXName = new XName(self::XSD_NS, 'anySimpleType');

        $this->anySimpleType_ =
            new PredefinedType($this, $anySimpleTypeXName, $this->anyType_);

        $this->globalTypes_[(string)$anySimpleTypeXName] =
            $this->anySimpleType_;

        // Add `xsi:type` to be `xsd:QName` if undefined.
        $xsiTypeXName = new XName(self::XSI_NS, 'type');

        if (!isset($this->globalAttrs_[(string)$xsiTypeXName])) {
            $this->globalAttrs_[(string)$xsiTypeXName] =
                new PredefinedAttr(
                    $this,
                    $xsiTypeXName,
                    $this->getGlobalType(new XName(self::XSD_NS, 'QName'))
                );
        }
    }
}
