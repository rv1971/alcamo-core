<?php

namespace alcamo\xpointer;

/*
 * @sa https://www.w3.org/TR/xptr-xmlns/
 */
class XmlnsPart implements PartInterface
{
    /*
     * @warning The imeplementation does not ensure the constraints defined in
     * https://www.w3.org/TR/xptr-framework/#nsContext.
     */
    public function process(array &$nsBindings, string $data, \DOMDocument $doc)
    {
        $a = explode('=', $data, 2);

        $nsBindings[trim($a[0])] = trim($a[1]);
    }
}
