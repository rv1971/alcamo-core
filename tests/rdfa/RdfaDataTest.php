<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;
use alcamo\iana\MediaType;
use alcamo\ietf\Lang;
use alcamo\time\Duration;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'FactoryTestAux.php';

class RdfaDataTest extends TestCase
{
    public const DC_NS = 'http://purl.org/dc/terms/';
    public const OWL_NS = 'http://www.w3.org/2002/07/owl#';

  /**
   * @dataProvider createProvider
   */
    public function testCreateFromFactory(
        $inputData,
        $expectedData,
        $expectedMap
    ) {
        $factory = new Factory();

        $this->testData(
            $factory->createRdfaData($inputData),
            $expectedData,
            $expectedMap
        );
    }

  /**
   * @dataProvider createProvider
   */
    public function testCreateFromRdfaData(
        $inputData,
        $expectedData,
        $expectedMap
    ) {
        $this->testData(
            RdfaData::newFromIterable($inputData),
            $expectedData,
            $expectedMap
        );
    }

    private function testData($data, $expectedData, $expectedMap)
    {
        $aux = new FactoryTestAux();

        $aux->testData($data, $expectedData);

        $this->assertSame($expectedData['html'], (string)$data->toHtmlNodes());

        $this->assertSame($expectedData['httpHeaders'], $data->toHttpHeaders());

        $this->assertSame($expectedMap, $data->getPrefixMap());
    }

    public function createProvider()
    {
        return [
            'simple' => [
                [
                    'dc:title' => 'Lorem ipsum',
                    'dc:format' => 'text/plain; charset=UTF-8',
                    'dc:source' => 'https://factory.test.example.com',
                    'owl:versionInfo' => '1.2.3'
                ],
                [
                    [
                        'key' => 'dc:title',
                        'class' => DcTitle::class,
                        'propertyCurie' => 'dc:title',
                        'propertyUri' => self::DC_NS . 'title',
                        'prefixMap' => [ 'dc' => self::DC_NS ],
                        'isResource' => false,
                        'label' => null,
                        'string' => 'Lorem ipsum',
                        'xmlAttrs' => [
                            'property' => 'dc:title',
                            'content' => 'Lorem ipsum'
                        ],
                        'html' =>
                        '<title property="dc:title">Lorem ipsum</title>',
                        'visibleHtml' => '<span property="dc:title">Lorem ipsum</span>',
                        'visibleHtmlWithoutRdfa' => 'Lorem ipsum',
                        'httpHeaders' => null
                    ],
                    [
                        'key' => 'dc:format',
                        'class' => DcFormat::class,
                        'propertyCurie' => 'dc:format',
                        'propertyUri' => self::DC_NS . 'format',
                        'prefixMap' => [ 'dc' => self::DC_NS ],
                        'isResource' => false,
                        'label' => null,
                        'string' => 'text/plain; charset="UTF-8"',
                        'xmlAttrs' => [
                            'property' => 'dc:format',
                            'content' => 'text/plain; charset="UTF-8"'
                        ],
                        'html' => '',
                        'visibleHtml' => '',
                        'visibleHtmlWithoutRdfa' => '',
                        'httpHeaders'
                        => [ 'Content-Type' => [ 'text/plain; charset="UTF-8"' ] ]
                    ],
                    [
                        'key' => 'dc:source',
                        'class' => DcSource::class,
                        'propertyCurie' => 'dc:source',
                        'propertyUri' => self::DC_NS . 'source',
                        'prefixMap' => [ 'dc' => self::DC_NS ],
                        'isResource' => true,
                        'label' => null,
                        'string' => 'https://factory.test.example.com',
                        'xmlAttrs' => [
                            'property' => 'dc:source',
                            'resource' => 'https://factory.test.example.com'
                        ],
                        'html' =>
                        '<link rel="dc:source canonical" href="https://factory.test.example.com"/>',
                        'visibleHtml' =>
                        '<a rel="dc:source canonical" href="https://factory.test.example.com">'
                        . 'https://factory.test.example.com</a>',
                        'visibleHtmlWithoutRdfa' =>
                        '<a href="https://factory.test.example.com">'
                        . 'https://factory.test.example.com</a>',
                        'httpHeaders' => [
                            'Link' => [ '<https://factory.test.example.com>; rel="canonical"' ]
                        ]
                    ],
                    [
                        'key' => 'owl:versionInfo',
                        'class' => OwlVersionInfo::class,
                        'propertyCurie' => 'owl:versionInfo',
                        'propertyUri' => self::OWL_NS . 'versionInfo',
                        'prefixMap' => [ 'owl' => self::OWL_NS ],
                        'isResource' => false,
                        'label' => null,
                        'string' => '1.2.3',
                        'xmlAttrs' => [
                            'property' => 'owl:versionInfo',
                            'content' => '1.2.3'
                        ],
                        'html' => '<meta property="owl:versionInfo" content="1.2.3"/>',
                        'visibleHtml' => '<span property="owl:versionInfo">1.2.3</span>',
                        'visibleHtmlWithoutRdfa' => '1.2.3',
                        'httpHeaders' => null
                    ],
                    [
                        'key' => 'meta:charset',
                        'class' => MetaCharset::class,
                        'propertyCurie' => 'meta:charset',
                        'isResource' => false,
                        'label' => null,
                        'string' => 'UTF-8',
                        'xmlAttrs' => [
                            'property' => 'meta:charset',
                            'content' => 'UTF-8'
                        ],
                        'html' =>
                        '<meta charset="UTF-8"/>',
                        'visibleHtml' =>
                        '<span property="meta:charset">UTF-8</span>',
                        'visibleHtmlWithoutRdfa' =>
                        'UTF-8',
                        'httpHeaders' => null
                    ],
                    'html' =>
                    '<meta charset="UTF-8"/>'
                    . '<title property="dc:title">Lorem ipsum</title>'
                    . '<link rel="dc:source canonical" href="https://factory.test.example.com"/>'
                    . '<meta property="owl:versionInfo" content="1.2.3"/>',
                    'httpHeaders' => [
                        'Content-Type' => [ 'text/plain; charset="UTF-8"' ],
                        'Link' => [ '<https://factory.test.example.com>; rel="canonical"' ]
                    ]
                ],
                [
                    'dc' => self::DC_NS,
                    'owl' => self::OWL_NS
                ]
            ]
        ];
    }

    /**
     * @dataProvider addProvider
     */
    public function testAdd($data1, $data2, $expectedData)
    {
        $data1 = RdfaData::newFromIterable($data1);
        $data2 = RdfaData::newFromIterable($data2);

        $data3 = $data1->add($data2);

        foreach ($data3 as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $subkey => $subitem) {
                    $this->assertSame((string)$subitem, $subkey);
                }
            }
        }

        $expectedData = RdfaData::newFromIterable($expectedData);

        $this->assertSame(
            (string)$expectedData->toHtmlNodes(),
            (string)$data3->toHtmlNodes()
        );
    }

    public function addProvider()
    {
        return [
            'simple' => [
                [
                    'dc:title' => 'Lorem ipsum',
                    'dc:creator' => 'Dilbert',
                    'dc:publisher' => [
                        'Garfield',
                        [ 'http://bob.example.org', true ]
                    ]
                ],
                [
                    'dc:identifier' => 'foo.bar.baz',
                    'dc:creator' => 'Tom',
                    'dc:publisher' => 'Alice',
                ],
                [
                    'dc:title' => 'Lorem ipsum',
                    'dc:creator' => [
                        'Dilbert',
                        'Tom'
                    ],
                    'dc:publisher' => [
                        'Garfield',
                        [ 'http://bob.example.org', true ],
                        'Alice'
                    ],
                    'dc:identifier' => 'foo.bar.baz'
                ]
            ]
        ];
    }

    /**
     * @dataProvider replaceProvider
     */
    public function testReplace($data1, $data2, $expectedData)
    {
        $data1 = RdfaData::newFromIterable($data1);
        $data2 = RdfaData::newFromIterable($data2);

        $data3 = $data1->replace($data2);

        $expectedData = RdfaData::newFromIterable($expectedData);

        $this->assertSame(
            (string)$expectedData->toHtmlNodes(),
            (string)$data3->toHtmlNodes()
        );
    }

    public function replaceProvider()
    {
        return [
        'simple' => [
        [
          'dc:title' => 'Lorem ipsum',
          'dc:creator' => 'Dilbert',
          'dc:publisher' => [
            'Garfield',
            [ 'http://bob.example.org', true ]
          ]
        ],
        [
          'dc:identifier' => 'foo.bar.baz',
          'dc:creator' => 'Tom',
          'dc:publisher' => 'Alice'
        ],
        [
          'dc:identifier' => 'foo.bar.baz',
          'dc:creator' => 'Tom',
          'dc:publisher' => 'Alice',
          'dc:title' => 'Lorem ipsum',
        ]
        ]
        ];
    }

    public function testAlterSession()
    {
        $data = '{ "header:cache-control": "public", "header-expires": "PT42M" }';

        exec(
            'PHPUNIT_COMPOSER_INSTALL="' . PHPUNIT_COMPOSER_INSTALL . '" php '
            . __DIR__ . DIRECTORY_SEPARATOR . "AlterSessionAux.php '$data'",
            $output
        );

        $this->assertSame($output, [ 'public', '42' ]);
    }
}
