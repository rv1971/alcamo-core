<?php

namespace alcamo\dom;

use alcamo\collection\PreventWriteArrayAccessTrait;
use alcamo\exception\{FileLoadFailed, Uninitialized};

/**
 * @brief DOM Document class having factory methods with validation.
 *
 * The ArrayAccess interface provides read access to elements by ID.
 */
class Document extends \DOMDocument implements \ArrayAccess
{
    use PreventWriteArrayAccessTrait;

    public const NS = [
        /// Dublin Core namespace.
        'dc' => 'http://purl.org/dc/terms/',

        /// OWL namespace.
        'owl' => 'http://www.w3.org/2002/07/owl#',

        /// RDF namespace.
        'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',

        /// RDFS namespace.
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',

        /// XHTML namespace.
        'xh' => 'http://www.w3.org/1999/xhtml',

        /// XHTML datatypes namespace.
        'xh11d' => 'http://www.w3.org/1999/xhtml/datatypes/',

        /// XML Namespace.
        'xml' => 'http://www.w3.org/XML/1998/namespace',

        /// XSD Namespace.
        'xsd' => 'http://www.w3.org/2001/XMLSchema',

        /// XSI Namespace.
        'xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
    ];

    public const NODE_CLASS = [
        'DOMAttr'    => Attr::class,
        'DOMElement' => Element::class,
        'DOMText'    => Text::class
    ];

    public const LIBXML_OPTIONS =
        LIBXML_COMPACT | LIBXML_NOBLANKS | LIBXML_NSCLEAN | LIBXML_PEDANTIC;

    public static function newFromUrl(string $url, ?int $libXmlOptions = null)
    {
        $doc = new static();

        $doc->loadUrl($url, $libXmlOptions);

        return $doc;
    }

    public static function newFromXmlText(
        string $xml,
        ?int $libXmlOptions = null
    ) {
        $doc = new static();

        $doc->loadXmlText($xml, $libXmlOptions);

        return $doc;
    }

    private static $docRegistry_ = [];

    private $xPath_;          ///< XPath object.
    private $xsltProcessor_;  ///< XSLTProcessor object or FALSE.

    public function __construct($version = null, $encoding = null)
    {
        parent::__construct($version, $encoding);

        foreach (static::NODE_CLASS as $baseClass => $extendedClass) {
            $this->registerNodeClass($baseClass, $extendedClass);
        }
    }

    public function loadUrl(string $url, ?int $libXmlOptions = null)
    {
        if (!$this->load($url, $libXmlOptions ?? static::LIBXML_OPTIONS)) {
            throw new FileLoadFailed($url);
        }

        return $this->afterLoad();
    }

    public function loadXmlText(string $xml, int $libXmlOptions = null)
    {
        if (!$this->loadXML($xml, $libXmlOptions ?? static::LIBXML_OPTIONS)) {
            throw new SyntaxError($xml);
        }

        return $this->afterLoad();
    }

    /** Ensure there is always a reference to the complete object so that it
     *  remains available through the `$ownerDocument` property of its
     *  nodes. */
    public function conserve(): self
    {
        return (self::$docRegistry_[spl_object_hash($this)] = $this);
    }

    /** Allow the object to be destroyed. */
    public function unconserve()
    {
        unset(self::$docRegistry_[spl_object_hash($this)]);
    }

    public function offsetExists($id)
    {
        return $this->getElementById($id) !== null;
    }

    public function offsetGet($id)
    {
        return $this->getElementById($id);
    }

    public function getXPath(): XPath
    {
        if (!isset($this->xPath_)) {
            if (!$this->documentElement) {
                /** @throw Uninitialized if called on an empty document. */
                throw new Uninitialized($this);
            }

            $this->xPath_ = new XPath($this);

            foreach (static::NS as $prefix => $uri) {
                $this->xPath_->registerNamespace($prefix, $uri);
            }
        }

        return $this->xPath_;
    }

    /// Run XPath query relative to root node.
    public function query(string $expr)
    {
        return $this->getXPath()->query($expr);
    }

    /// Run and evaluate XPath query relative to root node.
    public function evaluate(string $expr)
    {
        return $this->getXPath()->evaluate($expr);
    }

    /**
     * @brief XSLT processor based on the first xml-stylesheet processing
     * instruction, if any
     */
    public function getXsltProcessor()
    {
        if (!isset($this->xsltProcessor_)) {
            if (!$this->documentElement) {
                /** @throw Uninitialized if called on an empty document. */
                throw new Uninitialized($this);
            }

            $pi = $this->query('/processing-instruction("xml-stylesheet")')[0];

            if (!isset($pi)) {
                return ($this->xsltProcessor_ = false);
            }

            $pseudoAttrs = simplexml_load_string("<x {$pi->nodeValue}/>");

            if ($pseudoAttrs['type'] != 'text/xsl') {
                return ($this->xsltProcessor_ = false);
            }

            $this->xsltProcessor_ = new \XSLTProcessor();

            $xslFilename =
                substr(
                    $this->documentURI,
                    0,
                    strrpos($this->documentURI, '/') + 1
                )
                . $pseudoAttrs['href'];

            if (
                !$this->xsltProcessor_->importStylesheet(
                    self::newFromUrl($xslFilename)
                )
            ) {
                throw new FileLoadFailed($xslFilename);
            }
        }

        return $this->xsltProcessor_;
    }

    // Any initialization to be done after document loading
    protected function afterLoad()
    {
    }
}
