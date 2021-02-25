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
  /**
   * @dataProvider createProvider
   */
    public function testCreateFromFactory($inputData, $expectedData)
    {
        $factory = new Factory();

        $this->testData($factory->createRdfaData($inputData), $expectedData);
    }

  /**
   * @dataProvider createProvider
   */
    public function testCreateFromRdfaData($inputData, $expectedData)
    {
        $this->testData(RdfaData::newFromIterable($inputData), $expectedData);
    }

    private function testData($data, $expectedData)
    {
        $aux = new FactoryTestAux();

        $aux->testData($data, $expectedData);

        $this->assertSame($expectedData['html'], (string)$data->toHtmlNodes());

        $this->assertSame($expectedData['httpHeaders'], $data->toHttpHeaders());
    }

    public function createProvider()
    {
        return [
        'simple' => [
        [
          'dc:title' => 'Lorem ipsum',
          'dc:format' => 'text/plain; charset=UTF-8',
          'dc:source' => 'https://factory.test.example.com'
        ],
        [
          [
            'key' => 'dc:title',
            'class' => DcTitle::class,
            'property' => 'dc:title',
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
            'property' => 'dc:format',
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
            'property' => 'dc:source',
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
            'key' => 'meta:charset',
            'class' => MetaCharset::class,
            'property' => 'meta:charset',
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
          . '<link rel="dc:source canonical" href="https://factory.test.example.com"/>',
          'httpHeaders' => [
            'Content-Type' => [ 'text/plain; charset="UTF-8"' ],
            'Link' => [ '<https://factory.test.example.com>; rel="canonical"' ]
          ]
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

        $data1->add($data2);

        $expectedData = RdfaData::newFromIterable($expectedData);

        $this->assertSame(
            (string)$expectedData->toHtmlNodes(),
            (string)$data1->toHtmlNodes()
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

        $data1->replace($data2);

        $expectedData = RdfaData::newFromIterable($expectedData);

        $this->assertSame(
            (string)$expectedData->toHtmlNodes(),
            (string)$data1->toHtmlNodes()
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
            'php '
            . __DIR__ . DIRECTORY_SEPARATOR . "AlterSessionAux.php '$data'",
            $output
        );

        $this->assertSame($output, [ 'public', '42' ]);
    }
}
