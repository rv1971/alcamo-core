<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testIteration()
    {
        $doc = Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml');

        $bar = $doc['x'];

        $expectedIds = [
            'a', 'b', 'c', 'd',
            'datetime',
            'duration',
            'float',
            'lang',
            'longint',
            'curie',
            'safecurie',
            'uriorsafecurie1',
            'uriorsafecurie2'
        ];
        $i = 0;

        foreach ($bar as $baz) {
            $this->assertSame(
                $expectedIds[$i++],
                (string)$baz->getAttributeNodeNS(Document::NS['xml'], 'id')
            );
        }
    }
}
