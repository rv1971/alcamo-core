<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;
use alcamo\iana\MediaType;
use alcamo\ietf\Lang;
use alcamo\time\Duration;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'FactoryTestAux.php';

class FactoryTest extends TestCase
{
  /**
   * @dataProvider createArrayProvider
   */
    public function testCreateArray($inputData, $expectedData)
    {
        $factory = new Factory();

        $data = $factory->createArray($inputData);

        $aux = new FactoryTestAux();

        $aux->testData($data, $expectedData);
    }

    public function createArrayProvider()
    {
        return [
        'complete-objects/1' => [
        [
          'dc:abstract' => new DcAbstract('Lorem ipsum dolor sit amet.'),
          'dc:title' => null
        ],
        [
          [
            'key' => 'dc:abstract',
            'class' => DcAbstract::class,
            'propertyCurie' => 'dc:abstract',
            'isResource' => false,
            'label' => null,
            'string' => 'Lorem ipsum dolor sit amet.',
            'xmlAttrs' => [
              'property' => 'dc:abstract',
              'content' => 'Lorem ipsum dolor sit amet.'
            ],
            'html' =>
            '<meta property="dc:abstract" content="Lorem ipsum dolor sit amet." name="description"/>',
            'visibleHtml' =>
            '<span property="dc:abstract">Lorem ipsum dolor sit amet.</span>',
            'httpHeaders' => null
          ]
        ]
        ],

        'complete-objects/2' => [
        [
          'dc:conformsTo'
          => new DcConformsTo('https://semver.org/spec/v2.0.0.html', 'Semver 2.0'),

          'dc:created'
          => new DcCreated(new \DateTime('1970-01-01')),
        ],
        [
          [
            'key' => 'dc:conformsTo',
            'class' => DcConformsTo::class,
            'propertyCurie' => 'dc:conformsTo',
            'isResource' => true,
            'label' => 'Semver 2.0',
            'string' => 'https://semver.org/spec/v2.0.0.html',
            'xmlAttrs' => [
              'property' => 'dc:conformsTo',
              'resource' => 'https://semver.org/spec/v2.0.0.html'
            ],
            'html' =>
            '<link rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html"/>',
            'visibleHtml' =>
            '<a rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html">'
            . 'Semver 2.0</a>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'propertyCurie' => 'dc:created',
            'isResource' => false,
            'label' => null,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'visibleHtml' =>
            '<span property="dc:created">1970-01-01T00:00:00+00:00</span>',
            'httpHeaders' => null
          ]
        ]
        ],

        'complete-objects/3' => [
        [
          'dc:creator'
          => [
            new DcCreator('Dilbert'),
            new DcCreator('https://dilbert.example.org', true)
          ],

          'dc:format'
          => new DcFormat(MediaType::newFromString('application/xml')),

          'dc:identifier'
          => new DcIdentifier('foo.bar'),
        ],
        [
          [
            'key' => 'dc:creator',
            [
              'class' => DcCreator::class,
              'propertyCurie' => 'dc:creator',
              'isResource' => false,
              'label' => null,
              'string' => 'Dilbert',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                  'content' => 'Dilbert'
              ],
              'html' =>
              '<meta property="dc:creator" content="Dilbert" name="author"/>',
              'visibleHtml' => '<span property="dc:creator">Dilbert</span>',
              'httpHeaders' => null
            ],
            [
              'class' => DcCreator::class,
              'propertyCurie' => 'dc:creator',
              'isResource' => true,
              'label' => null,
              'string' => 'https://dilbert.example.org',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                'resource' => 'https://dilbert.example.org'
              ],
              'html' =>
              '<link rel="dc:creator author" href="https://dilbert.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:creator author" href="https://dilbert.example.org">'
              . 'https://dilbert.example.org</a>',
              'httpHeaders' => null
            ]
          ],

          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'propertyCurie' => 'dc:format',
            'isResource' => false,
            'label' => null,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders'
            => [ 'Content-Type' => [ 'application/xml' ] ]
          ],

          [
            'key' => 'dc:identifier',
            'class' => DcIdentifier::class,
            'propertyCurie' => 'dc:identifier',
            'isResource' => false,
            'label' => null,
            'string' => 'foo.bar',
            'xmlAttrs' => [
              'property' => 'dc:identifier',
              'content' => 'foo.bar'
            ],
            'html' => '<meta property="dc:identifier" content="foo.bar"/>',
            'visibleHtml' => '<span property="dc:identifier">foo.bar</span>',
            'httpHeaders' => null
          ]
        ]
        ],

        'complete-objects/4' => [
        [
          'dc:language'
          => new DcLanguage(Lang::newFromString('oc-FR')),

          'dc:modified'
          => new DcModified(new \DateTime('1971-02-03 04:05:06+01:00')),

          'dc:publisher'
          => [
            new DcPublisher('http://garfield.example.org', 'Garfield'),
            new DcPublisher('Garfield'),
            new DcPublisher('http://jerry.example.org', true)
          ],

          'dc:source'
          => new DcSource('https://factory.test.example.com')
        ],
        [
          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'propertyCurie' => 'dc:language',
            'isResource' => false,
            'label' => null,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' => '<meta property="dc:language" content="oc-FR"/>',
            'visibleHtml' => '<span property="dc:language">oc-FR</span>',
            'httpHeaders'
            => [ 'Content-Language' => [ 'oc-FR' ] ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'propertyCurie' => 'dc:modified',
            'isResource' => false,
            'label' => null,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'visibleHtml' =>
            '<span property="dc:modified">1971-02-03T04:05:06+01:00</span>',
            'httpHeaders' => [ 'Last-Modified' => [ 'Wed, 03 Feb 1971 04:05:06 +0100' ] ]
          ],

          [
            'key' => 'dc:publisher',
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => true,
              'label' => 'Garfield',
              'string' => 'http://garfield.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://garfield.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://garfield.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:publisher" href="http://garfield.example.org">Garfield</a>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => false,
              'label' => null,
              'string' => 'Garfield',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'content' => 'Garfield'
              ],
              'html' =>
              '<meta property="dc:publisher" content="Garfield"/>',
              'visibleHtml' =>
              '<span property="dc:publisher">Garfield</span>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => true,
              'label' => null,
              'string' => 'http://jerry.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://jerry.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://jerry.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:publisher" href="http://jerry.example.org">http://jerry.example.org</a>',
              'httpHeaders' => null
            ]
          ],

          [
            'key' => 'dc:source',
            'class' => DcSource::class,
            'propertyCurie' => 'dc:source',
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
            '<a rel="dc:source canonical" href="https://factory.test.example.com">https://factory.test.example.com</a>',
            'httpHeaders' => [
              'Link' => [ '<https://factory.test.example.com>; rel="canonical"' ]
            ]
          ]
        ]
        ],

        'complete-objects/5' => [
        [
          'dc:title'
          => new DcTitle('Lorem ipsum'),

          'header:cache-control'
          => new HeaderCacheControl('public'),

          'header:content-disposition'
          => new HeaderContentDisposition('baz.json'),

          'header:content-length'
          => HeaderContentLength::newFromFilename(
              __DIR__ . DIRECTORY_SEPARATOR . 'foo.txt'
          ),

          'header:expires'
          => new HeaderExpires(new Duration('P40D')),
        ],
        [
          [
            'key' => 'dc:title',
            'class' => DcTitle::class,
            'propertyCurie' => 'dc:title',
            'isResource' => false,
            'label' => null,
            'string' => 'Lorem ipsum',
            'xmlAttrs' => [
              'property' => 'dc:title',
              'content' => 'Lorem ipsum'
            ],
            'html' =>
            '<title property="dc:title">Lorem ipsum</title>',
            'visibleHtml' =>
            '<span property="dc:title">Lorem ipsum</span>',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:cache-control',
            'class' => HeaderCacheControl::class,
            'propertyCurie' => 'header:cache-control',
            'isResource' => false,
            'label' => null,
            'string' => 'public',
            'xmlAttrs' => [
              'property' => 'header:cache-control',
              'content' => 'public'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
                'Cache-Control' => [ 'public' ]
            ],
          ],

          [
            'key' => 'header:content-disposition',
            'class' => HeaderContentDisposition::class,
            'propertyCurie' => 'header:content-disposition',
            'isResource' => false,
            'label' => null,
            'string' => 'baz.json',
            'xmlAttrs' => [
              'property' => 'header:content-disposition',
              'content' => 'baz.json'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
              'Content-Disposition' => [ 'attachment; filename="baz.json"' ]
            ]
          ],

          [
            'key' => 'header:content-length',
            'class' => HeaderContentLength::class,
            'propertyCurie' => 'header:content-length',
            'isResource' => false,
            'label' => null,
            'string' => '12',
            'xmlAttrs' => [
              'property' => 'header:content-length',
              'content' => '12'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
              'Content-Length' => [ '12' ]
            ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'propertyCurie' => 'header:expires',
            'isResource' => false,
            'label' => null,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [ 'Expires' => '' ]
          ]
        ]
        ],

        'complete-objects/6' => [
        [
          'meta:charset'
          => new MetaCharset('UTF-8')
        ],
        [
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
            'html' => '<meta charset="UTF-8"/>',
            'visibleHtml' => '<span property="meta:charset">UTF-8</span>',
            'httpHeaders' => null
          ]
        ]
        ],

        'inner-objects/1' => [
        [
          'dc:abstract' => 'Lorem ipsum dolor sit amet.',

          'dc:conformsTo' =>
          [ [ 'https://semver.org/spec/v2.0.0.html', 'Semantic Versioning' ]],

          'dc:created' => new \DateTime('1970-01-01')
        ],
        [
          [
            'key' => 'dc:abstract',
            'class' => DcAbstract::class,
            'propertyCurie' => 'dc:abstract',
            'isResource' => false,
            'label' => null,
            'string' => 'Lorem ipsum dolor sit amet.',
            'xmlAttrs' => [
              'property' => 'dc:abstract',
              'content' => 'Lorem ipsum dolor sit amet.'
            ],
            'html' =>
            '<meta property="dc:abstract" content="Lorem ipsum dolor sit amet." name="description"/>',
            'visibleHtml' =>
            '<span property="dc:abstract">Lorem ipsum dolor sit amet.</span>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:conformsTo',
            'class' => DcConformsTo::class,
            'propertyCurie' => 'dc:conformsTo',
            'isResource' => true,
            'label' => 'Semantic Versioning',
            'string' => 'https://semver.org/spec/v2.0.0.html',
            'xmlAttrs' => [
              'property' => 'dc:conformsTo',
              'resource' => 'https://semver.org/spec/v2.0.0.html'
            ],
            'html' =>
            '<link rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html"/>',
            'visibleHtml' =>
            '<a rel="dc:conformsTo" href="https://semver.org/spec/v2.0.0.html">'
            . 'Semantic Versioning</a>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'propertyCurie' => 'dc:created',
            'isResource' => false,
            'label' => null,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'visibleHtml' =>
            '<span property="dc:created">1970-01-01T00:00:00+00:00</span>',
            'httpHeaders' => null
          ]
        ]
        ],

        'inner-objects/2' => [
        [
          'dc:creator' => [
            'Dilbert',
            [ 'https://dilbert.example.org', true ]
          ]
        ],
        [
          [
            'key' => 'dc:creator',
            [
              'class' => DcCreator::class,
              'propertyCurie' => 'dc:creator',
              'isResource' => false,
              'label' => null,
              'string' => 'Dilbert',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                  'content' => 'Dilbert'
              ],
              'html' =>
              '<meta property="dc:creator" content="Dilbert" name="author"/>',
              'visibleHtml' =>
              '<span property="dc:creator">Dilbert</span>',
              'httpHeaders' => null
            ],
            [
              'class' => DcCreator::class,
              'propertyCurie' => 'dc:creator',
              'isResource' => true,
              'label' => null,
              'string' => 'https://dilbert.example.org',
              'xmlAttrs' => [
                'property' => 'dc:creator',
                'resource' => 'https://dilbert.example.org'
              ],
              'html' =>
              '<link rel="dc:creator author" href="https://dilbert.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:creator author" href="https://dilbert.example.org">https://dilbert.example.org</a>',
              'httpHeaders' => null
            ]
          ]
        ]
        ],

        'inner-objects/3' => [
        [
          'dc:format' => MediaType::newFromString('application/xml'),

          'dc:identifier' => 'foo.bar',

          'dc:language' => Lang::newFromString('oc-FR'),

          'dc:modified' => new \DateTime('1971-02-03 04:05:06+01:00'),

          'dc:publisher' => [
            [ 'http://garfield.example.org', true ],
            'Garfield',
            [ 'http://jerry.example.org', true ]
          ]
        ],
        [
          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'propertyCurie' => 'dc:format',
            'isResource' => false,
            'label' => null,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders'
            => [ 'Content-Type' => [ 'application/xml' ] ]
          ],

          [
            'key' => 'dc:identifier',
            'class' => DcIdentifier::class,
            'propertyCurie' => 'dc:identifier',
            'isResource' => false,
            'label' => null,
            'string' => 'foo.bar',
            'xmlAttrs' => [
              'property' => 'dc:identifier',
              'content' => 'foo.bar'
            ],
            'html' =>
            '<meta property="dc:identifier" content="foo.bar"/>',
            'visibleHtml' =>
            '<span property="dc:identifier">foo.bar</span>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'propertyCurie' => 'dc:language',
            'isResource' => false,
            'label' => null,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' =>
            '<meta property="dc:language" content="oc-FR"/>',
            'visibleHtml' =>
            '<span property="dc:language">oc-FR</span>',
            'httpHeaders'
            => [ 'Content-Language' => [ 'oc-FR' ] ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'propertyCurie' => 'dc:modified',
            'isResource' => false,
            'label' => null,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'visibleHtml' =>
            '<span property="dc:modified">1971-02-03T04:05:06+01:00</span>',
            'httpHeaders' => [ 'Last-Modified' => [ 'Wed, 03 Feb 1971 04:05:06 +0100' ] ]
          ],

          [
            'key' => 'dc:publisher',
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => true,
              'label' => null,
              'string' => 'http://garfield.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://garfield.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://garfield.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:publisher" href="http://garfield.example.org">http://garfield.example.org</a>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => false,
              'label' => null,
              'string' => 'Garfield',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'content' => 'Garfield'
              ],
              'html' =>
              '<meta property="dc:publisher" content="Garfield"/>',
              'visibleHtml' =>
              '<span property="dc:publisher">Garfield</span>',
              'httpHeaders' => null
            ],
            [
              'class' => DcPublisher::class,
              'propertyCurie' => 'dc:publisher',
              'isResource' => true,
              'label' => null,
              'string' => 'http://jerry.example.org',
              'xmlAttrs' => [
                'property' => 'dc:publisher',
                'resource' => 'http://jerry.example.org'
              ],
              'html' =>
              '<link rel="dc:publisher" href="http://jerry.example.org"/>',
              'visibleHtml' =>
              '<a rel="dc:publisher" href="http://jerry.example.org">http://jerry.example.org</a>',
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

          'header:expires' => new Duration('P40D'),

          'meta:charset' => 'UTF-8'
        ],
        [
          [
            'key' => 'dc:source',
            'class' => DcSource::class,
            'propertyCurie' => 'dc:source',
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
            '<a rel="dc:source canonical" href="https://factory.test.example.com">https://factory.test.example.com</a>',
            'httpHeaders' => [
                'Link' => [ '<https://factory.test.example.com>; rel="canonical"' ]
            ]
          ],

          [
            'key' => 'dc:title',
            'class' => DcTitle::class,
            'propertyCurie' => 'dc:title',
            'isResource' => false,
            'label' => null,
            'string' => 'Lorem ipsum',
            'xmlAttrs' => [
              'property' => 'dc:title',
              'content' => 'Lorem ipsum'
            ],
            'html' =>
            '<title property="dc:title">Lorem ipsum</title>',
            'visibleHtml' =>
            '<span property="dc:title">Lorem ipsum</span>',
            'httpHeaders' => null
          ],

          [
            'key' => 'header:cache-control',
            'class' => HeaderCacheControl::class,
            'propertyCurie' => 'header:cache-control',
            'isResource' => false,
            'label' => null,
            'string' => 'public',
            'xmlAttrs' => [
              'property' => 'header:cache-control',
              'content' => 'public'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
                'Cache-Control' => [ 'public' ],
            ]
          ],

          [
            'key' => 'header:content-disposition',
            'class' => HeaderContentDisposition::class,
            'propertyCurie' => 'header:content-disposition',
            'isResource' => false,
            'label' => null,
            'string' => 'baz.json',
            'xmlAttrs' => [
              'property' => 'header:content-disposition',
              'content' => 'baz.json'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
              'Content-Disposition' => [ 'attachment; filename="baz.json"' ]
            ]
          ],

          [
            'key' => 'header:content-length',
            'class' => HeaderContentLength::class,
            'propertyCurie' => 'header:content-length',
            'isResource' => false,
            'label' => null,
            'string' => '123456',
            'xmlAttrs' => [
              'property' => 'header:content-length',
              'content' => '123456'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [
              'Content-Length' => [ '123456' ]
            ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'propertyCurie' => 'header:expires',
            'isResource' => false,
            'label' => null,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [ 'Expires' => '' ]
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

          'header:expires' => 'P40D',

          'dc:creator' => 'Alice'
        ],
        [
          [
            'key' => 'dc:created',
            'class' => DcCreated::class,
            'propertyCurie' => 'dc:created',
            'isResource' => false,
            'label' => null,
            'string' => '1970-01-01T00:00:00+00:00',
            'xmlAttrs' => [
              'property' => 'dc:created',
              'content' => '1970-01-01T00:00:00+00:00'
            ],
            'html' =>
            '<meta property="dc:created" content="1970-01-01T00:00:00+00:00"/>',
            'visibleHtml' =>
            '<span property="dc:created">1970-01-01T00:00:00+00:00</span>',
            'httpHeaders' => null
          ],

          [
            'key' => 'dc:format',
            'class' => DcFormat::class,
            'propertyCurie' => 'dc:format',
            'isResource' => false,
            'label' => null,
            'string' => 'application/xml',
            'xmlAttrs' => [
              'property' => 'dc:format',
              'content' => 'application/xml'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders'
            => [ 'Content-Type' => [ 'application/xml' ] ]
          ],

          [
            'key' => 'dc:language',
            'class' => DcLanguage::class,
            'propertyCurie' => 'dc:language',
            'isResource' => false,
            'label' => null,
            'string' => 'oc-FR',
            'xmlAttrs' => [
              'property' => 'dc:language',
              'content' => 'oc-FR'
            ],
            'html' =>
            '<meta property="dc:language" content="oc-FR"/>',
            'visibleHtml' =>
            '<span property="dc:language">oc-FR</span>',
            'httpHeaders'
            => [ 'Content-Language' => [ 'oc-FR' ] ]
          ],

          [
            'key' => 'dc:modified',
            'class' => DcModified::class,
            'propertyCurie' => 'dc:modified',
            'isResource' => false,
            'label' => null,
            'string' => '1971-02-03T04:05:06+01:00',
            'xmlAttrs' => [
              'property' => 'dc:modified',
              'content' => '1971-02-03T04:05:06+01:00'
            ],
            'html' =>
            '<meta property="dc:modified" content="1971-02-03T04:05:06+01:00"/>',
            'visibleHtml' =>
            '<span property="dc:modified">1971-02-03T04:05:06+01:00</span>',
            'httpHeaders' => [ 'Last-Modified' => [ 'Wed, 03 Feb 1971 04:05:06 +0100' ] ]
          ],

          [
            'key' => 'header:expires',
            'class' => HeaderExpires::class,
            'propertyCurie' => 'header:expires',
            'isResource' => false,
            'label' => null,
            'string' => 'P40D',
            'xmlAttrs' => [
              'property' => 'header:expires',
              'content' => 'P40D'
            ],
            'html' => '',
            'visibleHtml' => '',
            'httpHeaders' => [ 'Expires' => '' ]
          ],

          [
            'key' => 'dc:creator',
            'class' => DcCreator::class,
            'propertyCurie' => 'dc:creator',
            'isResource' => false,
            'label' => null,
            'string' => 'Alice',
            'xmlAttrs' => [
              'property' => 'dc:creator',
              'content' => 'Alice'
            ],
            'html' =>
            '<meta property="dc:creator" content="Alice" name="author"/>',
            'visibleHtml' =>
            '<span property="dc:creator">Alice</span>',
            'httpHeaders' => null
          ]
        ]
        ]
        ];
    }
}
