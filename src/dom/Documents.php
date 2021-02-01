<?php

namespace alcamo\dom;

use alcamo\collection\ReadonlyCollection;

/// Array of DOM documents indexed by dc:identifier.
class Documents extends ReadonlyCollection
{
    /**
     * If a key in $docs is a string, use it as key int he result
     * colelction. Otherwise, use the `dc:identifier` attribute in the
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
