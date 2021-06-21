<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;

class FactoryTestAux extends TestCase
{
    public function testData($data, $expectedData)
    {
        $i = 0;
        foreach ($data as $key => $item) {
            $expectedItem = $expectedData[$i++];

            $this->assertSame($expectedItem['key'], $key);

            if (is_array($item)) {
                $j = 0;
                foreach ($item as $subkey => $subitem) {
                    $this->assertSame((string)$subitem, $subkey);
                    $this->testItem($subitem, $expectedItem[$j++]);
                }
            } else {
                $this->testItem($item, $expectedItem);
            }
        }
    }

    private function testItem($item, $expectedItem)
    {
        $expectedItemClass = $expectedItem['class'];

        $this->assertSame(
            $expectedItem['propertyCurie'],
            $item->getPropertyCurie()
        );

        if (isset($expectedItem['propertyUri'])) {
            $this->assertSame(
                $expectedItem['propertyUri'],
                $item->getPropertyUri()
            );
        }

        if (isset($expectedItem['prefixMap'])) {
            $this->assertSame(
                $expectedItem['prefixMap'],
                $item->getPrefixMap()
            );
        }

        $this->assertInstanceOf($expectedItemClass, $item);

        if (defined("$expectedItemClass::OBJECT_CLASS")) {
            $this->assertInstanceOf(
                $expectedItemClass::OBJECT_CLASS,
                $item->getObject()
            );
        }

        $this->assertSame($expectedItem['isResource'], $item->isResource());

        $this->assertSame(
            $expectedItem['label'],
            $item->getResourceLabel()
        );

        $this->assertSame($expectedItem['string'], (string)$item);

        $this->assertSame($expectedItem['xmlAttrs'], $item->toXmlAttrs());

        $this->assertSame($expectedItem['html'], (string)$item->toHtmlNodes());

        $this->assertSame(
            $expectedItem['visibleHtml'],
            (string)$item->toVisibleHtmlNodes(true)
        );

        if (isset($expectedItem['visibleHtmlWithoutRdfa'])) {
            $this->assertSame(
                $expectedItem['visibleHtmlWithoutRdfa'],
                (string)$item->toVisibleHtmlNodes()
            );
        }

        if (
            isset($expectedItem['httpHeaders'])
            && array_keys($expectedItem['httpHeaders']) == [ 'Expires' ]
        ) {
            $this->assertSame(
                array_keys($expectedItem['httpHeaders']),
                array_keys($item->toHttpHeaders())
            );

            $this->assertTrue(
                (new \DateTimeImmutable($item->toHttpHeaders()['Expires'][0]))
                    ->format('U')
                > time()
            );
        } else {
            $this->assertSame(
                $expectedItem['httpHeaders'],
                $item->toHttpHeaders()
            );
        }
    }
}
