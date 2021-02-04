<?php

namespace alcamo\dom\schema;

use GuzzleHttp\Psr7\UriResolver;
use alcamo\dom\xsd\Document;
use alcamo\ietf\Uri;

class Schema
{
    private $xsds_ = []; ///< Map of Uris to XSD documents

    /** @throw AbsoluteUriNeeded when an XSD has a non-absolute URI. */
    public function __construct(iterable $xsds)
    {
        // always load XMLSchema.xsd
        $xmlSchemaXsd = Document::newFromUrl(
            'file://' . realpath(
                __DIR__ . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . '..' . DIRECTORY_SEPARATOR
                . 'xsd' . DIRECTORY_SEPARATOR . 'XMLSchema.xsd'
            ),
            true
        );

        $xsds_[$xmlSchemaXsd->documentURI] = $xmlSchemaXsd;

        foreach ( $xsds as $xsd ) {
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
                    $this->xsds_[$url] = Document::newFromUrl($url, true);
                }
            }
        }

        $this->initGlobals();
    }

    public function getXsds(): array
    {
        return $this->xsds_;
    }

    private function initGlobals()
    {
        foreach ($this->xsds_ as $xsd) {
            foreach($xsd as $elem) {
                if ($elem->namespaceURI != Document::NS['xsd']) {
                    continue;
                }

                switch ($elem->localName) {
                    case 'attribute':
                        break;

                    case 'attributeGroup':
                        break;

                    case 'complexType':
                    case 'simpleType':
                        break;

                    case 'element':
                        break;

                    case 'group':
                        break;
                }
            }
        }
    }
}
