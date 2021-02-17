<?php

namespace alcamo\dom\psvi;

use PHPUnit\Framework\TestCase;
use alcamo\dom\schema\Schema;
use alcamo\xml\XName;

class DocumentTest extends TestCase
{
    public const XSD_NS = 'http://www.w3.org/2001/XMLSchema';

    public static $doc;

    public static function setUpBeforeClass(): void
    {
        self::$doc = Document::newFromUrl(
            'file://' . dirname(__DIR__) . '/baz.xml'
        );
    }

    public function testGetSchema()
    {
        $this->assertInstanceOf(Schema::class, self::$doc->getSchema());

        $basenames = [];

        foreach (self::$doc->getSchema()->getXsds() as $url => $xsd) {
            $basenames[] = basename($url);
        }

        $this->assertSame([ 'XMLSchema.xsd', 'xml.xsd' ], $basenames);
    }

    /**
     * @dataProvider getAttrConvertersProvider
     */
    public function testGetAttrConverters(
        $nsName,
        $localName,
        $expectedConverter
    ) {
        $attrType = self::$doc->getSchema()
            ->getGlobalType(new XName($nsName, $localName));

        $converter = self::$doc->getAttrConverters()->lookup($attrType);

        if (isset($converter)) {
            $converter = explode('::', $converter)[1];
        }

        $this->assertSame($expectedConverter, $converter);
    }

    public function getAttrConvertersProvider()
    {
        return [
            [ self::XSD_NS, 'anyURI', 'toUri' ],
            [ self::XSD_NS, 'string', null ],
            [ self::XSD_NS, 'duration', 'toDuration' ],
            [ self::XSD_NS, 'unsignedByte', 'toInt' ]
        ];
    }
}
