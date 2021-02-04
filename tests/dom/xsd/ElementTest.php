<?php

namespace alcamo\dom\xsd;

use PHPUnit\Framework\TestCase;
use alcamo\xml\XName;

class ElementTest extends TestCase
{
    /**
     * @dataProvider getUniqueNameProvider
     */
    public function testGetUniqueName($elem, $expectedName)
    {
        $this->assertEquals($expectedName, $elem->getUniqueName());
    }

    public function getUniqueNameProvider()
    {
        $doc = Document::newFromUrl(
            dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR
            . 'xsd' . DIRECTORY_SEPARATOR . 'XMLSchema.xsd'
        )->conserve();

        return [
            'name' => [
                $doc->query('//*[@name = "openAttrs"]')[0],
                new XName(Document::NS['xsd'], 'openAttrs')
            ],
            'name-1' => [
                $doc->query('//*[@name = "openAttrs"]/*')[0],
                new XName(Document::NS['xsd'], 'openAttrs/1')
            ],
            'name-2' => [
                $doc->query('//*[@name = "openAttrs"]/*')[1],
                new XName(Document::NS['xsd'], 'openAttrs/2')
            ],
            'name-2-1-1' => [
                $doc->query('//*[@name = "openAttrs"]/*[2]/*/*')[0],
                new XName(Document::NS['xsd'], 'openAttrs/2/1/1')
            ]
        ];
    }
}
