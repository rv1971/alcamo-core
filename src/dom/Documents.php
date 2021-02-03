<?php

namespace alcamo\dom;

use alcamo\collection\ReadonlyCollection;

/// Array of DOM documents indexed by dc:identifier.
class Documents extends ReadonlyCollection
{
    public static function newFromGlob(
        string $pattern,
        ?int $libXmlOptions = null
    ): self {
        $docs = [];

        foreach (glob($pattern, GLOB_NOSORT | GLOB_BRACE) as $path) {
            $doc = Document::newFromUrl($path, $libXmlOptions);

            $key = $doc->documentElement->getAttributeNS(
                Document::NS['dc'],
                'identifier'
            );

            if ($key === '') {
                $key = pathinfo($path, PATHINFO_FILENAME);
            }

            $docs[$key] = $doc;
        }

        return new self($docs);
    }

    /**
     * If a key in $docs is a string, use it as key int he result
     * collection. Otherwise, use the `dc:identifier` attribute in the
     * document element.
     */
    public function __construct(iterable $docs)
    {
        $docs2 = [];

        foreach ($docs as $key => $doc) {
            $key = is_string($key)
                ? $key
                : $doc->documentElement->getAttributeNS(
                    Document::NS['dc'],
                    'identifier'
                );

            $docs2[$key] = $doc;
        }

        parent::__construct($docs2);
    }
}