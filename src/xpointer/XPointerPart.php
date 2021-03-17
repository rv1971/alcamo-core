<?php

namespace alcamo\xpointer;

/**
 * @warning Extensions to XPath 1.0 are not supported.
 *
 * @sa https://www.w3.org/TR/xptr-xpointer/
 */
class XpointerPart implements PartInterface
{
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    ) {
        $xPath = new \DOMXPath($doc);

        foreach ($nsBindings as $prefix => $nsName) {
            $xPath->registerNamespace($prefix, $nsName);
        }

        $result = $xPath->evaluate($schemeData);

        return $result === false || !isset($result[0]) ? null : $result;
    }
}
