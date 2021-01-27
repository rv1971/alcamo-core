<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;

use alcamo\exception\SyntaxError;
use alcamo\iana\MediaType;
use alcamo\ietf\Lang;
use alcamo\time\Duration;

class FactoryTest extends TestCase {
  /**
   * @dataProvider createArrayProvider
   */
  public function testCreateArray( $inputData, $expectedData ) {
    $factory = new Factory();

    $data = $factory->createArray( $inputData );

    $i = 0;
    foreach ( $data as $key => $item ) {
      $expectedItem = $expectedData[$i++];

      $this->assertSame( $expectedItem['key'], $key );

      if ( is_array( $item ) ) {
        $j = 0;
        foreach ( $item as $subitem ) {
          $this->testItem_( $subitem, $expectedItem[$j++] );
        }
      } else {
        $this->testItem_( $item, $expectedItem );
      }
    }
  }

  private function testItem_( $item, $expectedItem ) {
    $expectedItemClass = $expectedItem['class'];

    $this->assertSame( $expectedItem['property'], $item->getProperty() );
    $this->assertInstanceOf( $expectedItemClass, $item );

    if ( defined( "$expectedItemClass::OBJECT_CLASS" ) ) {
      $this->assertInstanceOf(
        $expectedItemClass::OBJECT_CLASS, $item->getObject()
      );
    }

    $this->assertSame( $expectedItem['isResource'], $item->isResource() );

    $this->assertSame( $expectedItem['string'], (string)$item );

    $this->assertSame( $expectedItem['xmlAttrs'], $item->toXmlAttrs() );

    $this->assertSame( $expectedItem['html'], (string)$item->toHtmlNodes() );

    $this->assertSame( $expectedItem['httpHeaders'], $item->toHttpHeaders() );
  }

  public function createArrayProvider() {
    return [
      'complete-objects/1' => [
        [
          'dc:abstract'
          => new DcAbstract( 'Lorem ipsum dolor sit amet.' )
        ],
        [
          [
            'key' => 'dc:abstract',
            'class' => DcAbstract::class,
            'property' => 'dc:abstract',
            'isResource' => false,
            'string' => 'Lorem ipsum dolor sit amet.',
            'xmlAttrs' => [
              'property' => 'dc:abstract',
              'content' => 'Lorem ipsum dolor sit amet.'
            ],
            'html' =>
            '<meta property="dc:abstract" content="Lorem ipsum dolor sit amet." name="description"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'complete-objects/2' => [
        [
          'dc:conformsTo'
          => new DcConformsTo( 'https://semver.org/spec/v2.0.0.html' ),

          'dc:created'
          => new DcCreated( new \DateTime( '1970-01-01' ) ),
        ],
        [
          [
            'key' => 'dc:conformsTo',
            'class' => DcConformsTo::class,
            'property' => 'dc:conformsTo',
            'isResource' => true,
            'string' => 'https://semver.org/spec/v2.0.0.html',
            'xmlAttrs' => [
              'property' => 'dc:conformsTo',
              'resource' => 'https://semver.org/spec/v2.0.0.html'
            ],
            'html' =>
            '<link rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html"/>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'property' => 'dc:created',
            'isResource' => false,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'complete-objects/3' => [
        [
          'dc:creator'
          => [
            new DcCreator( 'Dilbert', false ),
            new DcCreator( 'https://dilbert.example.org', true )
          ],

          'dc:format'
          => new DcFormat( MediaType::newFromString( 'application/xml' ) ),

          'dc:identifier'
          => new DcIdentifier( 'foo.bar' ),
        ],
        [
          [
            'key' => 'dc:creator',
            [
              'class' => DcCreator::class,
              'property' => 'dc:creator',
              'isResource' => false,
              'string' => 'Dilbert',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                  'content' => 'Dilbert'
              ],
              'html' =>
              '<meta property="dc:creator" content="Dilbert" name="author"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcCreator::class,
              'property' => 'dc:creator',
              'isResource' => true,
              'string' => 'https://dilbert.example.org',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                'resource' => 'https://dilbert.example.org'
              ],
              'html' =>
              '<link rel="dc:creator author" href="https://dilbert.example.org"/>',
              'httpHeaders' => null
            ]
          ],

          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'property' => 'dc:format',
            'isResource' => false,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' =>
            '<meta property="dc:format" content="application/xml"/>',
            'httpHeaders'
            => [ 'Content-Type' => 'Content-Type: application/xml' ]
          ],

          [
            'key' => 'dc:identifier',
            'class' => DcIdentifier::class,
            'property' => 'dc:identifier',
            'isResource' => false,
            'string' => 'foo.bar',
            'xmlAttrs' => [
              'property' => 'dc:identifier',
              'content' => 'foo.bar'
            ],
            'html' =>
            '<meta property="dc:identifier" content="foo.bar"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'complete-objects/4' => [
        [
          'dc:language'
          => new DcLanguage( Lang::newFromString( 'oc-FR' ) ),

          'dc:modified'
          => new DcModified( new \DateTime( '1971-02-03 04:05:06+01:00' ) ),

          'dc:publisher'
          => [
            new DcPublisher( 'http://garfield.example.org', true ),
            new DcPublisher( 'Garfield', false ),
            new DcPublisher( 'http://jerry.example.org', true )
          ],

          'dc:source'
          => new DcSource( 'https://factory.test.example.com' )
        ],
        [
          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'property' => 'dc:language',
            'isResource' => false,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' =>
            '<meta property="dc:language" content="oc-FR"/>',
            'httpHeaders'
            => [ 'Content-Language' => 'Content-Language: oc-FR' ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'property' => 'dc:modified',
            'isResource' => false,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'httpHeaders' => [ 'Last-Modified' => 'Last-Modified: Wed, 03 Feb 1971 04:05:06 +0100' ]
          ],

          [
            'key' => 'dc:publisher',
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => true,
              'string' => 'http://garfield.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://garfield.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://garfield.example.org"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => false,
              'string' => 'Garfield',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'content' => 'Garfield'
              ],
              'html' =>
              '<meta property="dc:publisher" content="Garfield"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => true,
              'string' => 'http://jerry.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://jerry.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://jerry.example.org"/>',
              'httpHeaders' => null
            ]
          ],

          [
            'key' => 'dc:source',
            'class' => DcSource::class,
            'property' => 'dc:source',
            'isResource' => true,
            'string' => 'https://factory.test.example.com',
            'xmlAttrs' => [
              'property' => 'dc:source',
              'resource' => 'https://factory.test.example.com'
            ],
            'html' =>
            '<link rel="dc:source canonical" href="https://factory.test.example.com"/>',
            'httpHeaders' => [
              'Link' => 'Link: <https://factory.test.example.com>; rel="canonical"'
            ]
          ]
        ]
      ],

      'complete-objects/5' => [
        [
          'dc:title'
          => new DcTitle( 'Lorem ipsum' ),

          'header:cache-control'
          => new HeaderCacheControl( 'public' ),

          'header:content-disposition'
          => new HeaderContentDisposition( 'baz.json' ),

          'header:content-length'
          => HeaderContentLength::newFromFilename(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.txt' ),

          'header:expires'
          => new HeaderExpires( new Duration( 'P40D' ) ),
        ],
        [
          [
            'key' => 'dc:title',
            'class' => DcTitle::class,
            'property' => 'dc:title',
            'isResource' => false,
            'string' => 'Lorem ipsum',
            'xmlAttrs' => [
              'property' => 'dc:title',
              'content' => 'Lorem ipsum'
            ],
            'html' =>
            '<title property="dc:title">Lorem ipsum</title>',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:cache-control',
            'class' => HeaderCacheControl::class,
            'property' => 'header:cache-control',
            'isResource' => false,
            'string' => 'public',
            'xmlAttrs' => [
              'property' => 'header:cache-control',
              'content' => 'public'
            ],
            'html' => '',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:content-disposition',
            'class' => HeaderContentDisposition::class,
            'property' => 'header:content-disposition',
            'isResource' => false,
            'string' => 'baz.json',
            'xmlAttrs' => [
              'property' => 'header:content-disposition',
              'content' => 'baz.json'
            ],
            'html' => '',
            'httpHeaders' => [
              'Content-Disposition' => 'Content-Disposition: attachment; filename="baz.json"'
            ]
          ],

          [
            'key' => 'header:content-length',
            'class' => HeaderContentLength::class,
            'property' => 'header:content-length',
            'isResource' => false,
            'string' => '12',
            'xmlAttrs' => [
              'property' => 'header:content-length',
              'content' => '12'
            ],
            'html' => '',
            'httpHeaders' => [
              'Content-Length' => 'Content-Length: 12'
            ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'property' => 'header:expires',
            'isResource' => false,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'httpHeaders' => null
          ]
        ]
      ],

      'complete-objects/6' => [
        [
          'meta:charset'
          => new MetaCharset( 'UTF-8' )
        ],
        [
          [
            'key' => 'meta:charset',
            'class' => MetaCharset::class,
            'property' => 'meta:charset',
            'isResource' => false,
            'string' => 'UTF-8',
            'xmlAttrs' => [
              'property' => 'meta:charset',
              'content' => 'UTF-8'
            ],
            'html' =>
            '<meta charset="UTF-8"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'inner-objects/1' => [
        [
          'dc:abstract' => 'Lorem ipsum dolor sit amet.',

          'dc:conformsTo' => 'https://semver.org/spec/v2.0.0.html',

          'dc:created' => new \DateTime( '1970-01-01' )
        ],
        [
          [
            'key' => 'dc:abstract',
            'class' => DcAbstract::class,
            'property' => 'dc:abstract',
            'isResource' => false,
            'string' => 'Lorem ipsum dolor sit amet.',
            'xmlAttrs' => [
              'property' => 'dc:abstract',
              'content' => 'Lorem ipsum dolor sit amet.'
            ],
            'html' =>
            '<meta property="dc:abstract" content="Lorem ipsum dolor sit amet." name="description"/>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:conformsTo',
            'class' => DcConformsTo::class,
            'property' => 'dc:conformsTo',
            'isResource' => true,
            'string' => 'https://semver.org/spec/v2.0.0.html',
            'xmlAttrs' => [
              'property' => 'dc:conformsTo',
              'resource' => 'https://semver.org/spec/v2.0.0.html'
            ],
            'html' =>
            '<link rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html"/>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'property' => 'dc:created',
            'isResource' => false,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'inner-objects/2' => [
        [
          'dc:creator' => [
            [ 'Dilbert', false ],
            [ 'https://dilbert.example.org', true ]
          ]
        ],
        [
          [
            'key' => 'dc:creator',
            [
              'class' => DcCreator::class,
              'property' => 'dc:creator',
              'isResource' => false,
              'string' => 'Dilbert',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                  'content' => 'Dilbert'
              ],
              'html' =>
              '<meta property="dc:creator" content="Dilbert" name="author"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcCreator::class,
              'property' => 'dc:creator',
              'isResource' => true,
              'string' => 'https://dilbert.example.org',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                'resource' => 'https://dilbert.example.org'
              ],
              'html' =>
              '<link rel="dc:creator author" href="https://dilbert.example.org"/>',
              'httpHeaders' => null
            ]
          ]
        ]
      ],

      'inner-objects/3' => [
        [
          'dc:format' => MediaType::newFromString( 'application/xml' ),

          'dc:identifier' => 'foo.bar',

          'dc:language' => Lang::newFromString( 'oc-FR' ),

          'dc:modified' => new \DateTime( '1971-02-03 04:05:06+01:00' ),

          'dc:publisher' => [
            [ 'http://garfield.example.org', true ],
            [ 'Garfield', false ],
            [ 'http://jerry.example.org', true ]
          ]
        ],
        [
          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'property' => 'dc:format',
            'isResource' => false,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' =>
            '<meta property="dc:format" content="application/xml"/>',
            'httpHeaders'
            => [ 'Content-Type' => 'Content-Type: application/xml' ]
          ],

          [
            'key' => 'dc:identifier',
            'class' => DcIdentifier::class,
            'property' => 'dc:identifier',
            'isResource' => false,
            'string' => 'foo.bar',
            'xmlAttrs' => [
              'property' => 'dc:identifier',
              'content' => 'foo.bar'
            ],
            'html' =>
            '<meta property="dc:identifier" content="foo.bar"/>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'property' => 'dc:language',
            'isResource' => false,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' =>
            '<meta property="dc:language" content="oc-FR"/>',
            'httpHeaders'
            => [ 'Content-Language' => 'Content-Language: oc-FR' ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'property' => 'dc:modified',
            'isResource' => false,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'httpHeaders' => [ 'Last-Modified' => 'Last-Modified: Wed, 03 Feb 1971 04:05:06 +0100' ]
          ],

          [
            'key' => 'dc:publisher',
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => true,
              'string' => 'http://garfield.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://garfield.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://garfield.example.org"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => false,
              'string' => 'Garfield',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'content' => 'Garfield'
              ],
              'html' =>
              '<meta property="dc:publisher" content="Garfield"/>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'property' => 'dc:publisher',
              'isResource' => true,
              'string' => 'http://jerry.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://jerry.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://jerry.example.org"/>',
              'httpHeaders' => null
            ]
          ]
        ]
      ],

      'inner-objects/4' => [
        [
          'dc:source' => 'https://factory.test.example.com',

          'dc:title' => 'Lorem ipsum',

          'header:cache-control' => 'public',

          'header:content-disposition' => 'baz.json',

          'header:content-length' => 123456,

          'header:expires' => new Duration( 'P40D' ),

          'meta:charset' => 'UTF-8'
        ],
        [
          [
            'key' => 'dc:source',
            'class' => DcSource::class,
            'property' => 'dc:source',
            'isResource' => true,
            'string' => 'https://factory.test.example.com',
            'xmlAttrs' => [
              'property' => 'dc:source',
              'resource' => 'https://factory.test.example.com'
            ],
            'html' =>
            '<link rel="dc:source canonical" href="https://factory.test.example.com"/>',
            'httpHeaders' => [
              'Link' => 'Link: <https://factory.test.example.com>; rel="canonical"'
            ]
          ],

          [
            'key' => 'dc:title',
            'class' => DcTitle::class,
            'property' => 'dc:title',
            'isResource' => false,
            'string' => 'Lorem ipsum',
            'xmlAttrs' => [
              'property' => 'dc:title',
              'content' => 'Lorem ipsum'
            ],
            'html' =>
            '<title property="dc:title">Lorem ipsum</title>',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:cache-control',
            'class' => HeaderCacheControl::class,
            'property' => 'header:cache-control',
            'isResource' => false,
            'string' => 'public',
            'xmlAttrs' => [
              'property' => 'header:cache-control',
              'content' => 'public'
            ],
            'html' => '',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:content-disposition',
            'class' => HeaderContentDisposition::class,
            'property' => 'header:content-disposition',
            'isResource' => false,
            'string' => 'baz.json',
            'xmlAttrs' => [
              'property' => 'header:content-disposition',
              'content' => 'baz.json'
            ],
            'html' => '',
            'httpHeaders' => [
              'Content-Disposition' => 'Content-Disposition: attachment; filename="baz.json"'
            ]
          ],

          [
            'key' => 'header:content-length',
            'class' => HeaderContentLength::class,
            'property' => 'header:content-length',
            'isResource' => false,
            'string' => '123456',
            'xmlAttrs' => [
              'property' => 'header:content-length',
              'content' => '123456'
            ],
            'html' => '',
            'httpHeaders' => [
              'Content-Length' => 'Content-Length: 123456'
            ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'property' => 'header:expires',
            'isResource' => false,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'httpHeaders' => null
          ],

          [
            'key' => 'meta:charset',
            'class' => MetaCharset::class,
            'property' => 'meta:charset',
            'isResource' => false,
            'string' => 'UTF-8',
            'xmlAttrs' => [
              'property' => 'meta:charset',
              'content' => 'UTF-8'
            ],
            'html' =>
            '<meta charset="UTF-8"/>',
            'httpHeaders' => null
          ]
        ]
      ],

      'no-objects' => [
        [
          'dc:created' => '1970-01-01',

          'dc:format' => 'application/xml',

          'dc:language' => 'oc-FR',

          'dc:modified' => '1971-02-03 04:05:06+01:00',

          'header:expires' => 'P40D'
        ],
        [
          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'property' => 'dc:created',
            'isResource' => false,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'property' => 'dc:format',
            'isResource' => false,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' =>
            '<meta property="dc:format" content="application/xml"/>',
            'httpHeaders'
            => [ 'Content-Type' => 'Content-Type: application/xml' ]
          ],

          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'property' => 'dc:language',
            'isResource' => false,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' =>
            '<meta property="dc:language" content="oc-FR"/>',
            'httpHeaders'
            => [ 'Content-Language' => 'Content-Language: oc-FR' ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'property' => 'dc:modified',
            'isResource' => false,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'httpHeaders' => [ 'Last-Modified' => 'Last-Modified: Wed, 03 Feb 1971 04:05:06 +0100' ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'property' => 'header:expires',
            'isResource' => false,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'httpHeaders' => null
          ]
        ]
      ]
    ];
  }
}
