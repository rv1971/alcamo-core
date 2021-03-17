<?php

namespace alcamo\xpointer;

/**
 * @sa https://www.w3.org/TR/xptr-xmlns/
 */
class XmlnsPart implements PartInterface
{
    /*
     * @warning The imeplementation does not ensure the constraints defined in
     * https://www.w3.org/TR/xptr-framework/#nsContext.
     */
    public function process(
        array &$nsBindings,
        string $schemeData,
        \DOMDocument $doc
    ) {
        $a = explode('=', $schemeData, 2);

        $nsBindings[rtrim($a[0])] = ltrim($a[1]);
    }
}
