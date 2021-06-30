<?php

namespace alcamo\iana;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\exception\{FileNotFound, InvalidEnumerator, SyntaxError};

class MediaTypeTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct($type, $subtype, $params, $expectedString)
    {
        $mediaType = new MediaType($type, $subtype, $params);

        $this->assertSame(strtolower($type), $mediaType->getType());
        $this->assertSame(strtolower($subtype), $mediaType->getSubtype());

        $this->assertEquals($expectedString, (string)$mediaType);
    }

    public function constructProvider()
    {
        return [
        'no-params' => [ 'text', 'plain', null, 'text/plain' ],
        'uppercase' => [ 'ImaGE', 'pNg', null, 'image/png' ],
        'params' => [
        'text',
        'plain',
        new Map([ 'charset' => 'UTF-8' ]),
        'text/plain; charset="UTF-8"'
        ],
        'uppercase-params' => [
        'text',
        'plain',
        [ 'charset' => 'ISO-8859-1', 'FOO' => 'bar' ],
        'text/plain; charset="ISO-8859-1"; foo="bar"'
        ]
        ];
    }

    public function testConstructException()
    {
        $this->expectException(InvalidEnumerator::class);
        $this->expectExceptionMessage(
            'Invalid value "foo", expected one of: "'
            . implode('", "', MediaType::TOP_LEVEL_TYPES)
            . '"; not a valid top-level media type'
        );

        $comment = new MediaType('foo', 'bar');
    }

  /**
   * @dataProvider newFromStringProvider
   */
    public function testNewFromString(
        $string,
        $expectedType,
        $expectedSubtype,
        $expectedParams,
        $expectedString
    ) {
        $mediaType = MediaType::newFromString($string);

        $this->assertSame($expectedType, $mediaType->getType());
        $this->assertSame($expectedSubtype, $mediaType->getSubtype());
        $this->assertSame($expectedParams, $mediaType->getParams());

        $this->assertEquals($expectedString, (string)$mediaType);
    }

    public function newFromStringProvider()
    {
        return [
        'simple' => [ 'text/plain', 'text', 'plain', [], 'text/plain' ],
        'with-spaces' => [
        " TEXT\t/ csv   ", 'text', 'csv', [], 'text/csv'
        ],
        'with-spaces-and-params' => [
        " text\t/ csv ; charset=us-ascii; header=\"present\"",
        'text',
        'csv',
        [ 'charset' => 'us-ascii', 'header' => 'present' ],
        'text/csv; charset="us-ascii"; header="present"'
        ],
        'with-quoted-pair' => [
        'application/FOO; bar="x\"\\\\y"',
        'application',
        'foo',
        [ 'bar' => 'x"\y' ],
        'application/foo; bar="x\"\\\\y"'
        ],
        'with-folding' => [
        'application/bar; baz="\"lorem' . "\r\n " . '<b>ipsum</b>\""',
        'application',
        'bar',
        [ 'baz' => '"lorem <b>ipsum</b>"' ],
        'application/bar; baz="\"lorem <b>ipsum</b>\""'
        ]
        ];
    }

  /**
   * @dataProvider newFromFilenameProvider
   */
    public function testNewFromFilename(
        $filename,
        $expectedType,
        $expectedSubtype,
        $expectedParams,
        $expectedString
    ) {
        $mediaType = MediaType::newFromFilename($filename);

        $this->assertSame($expectedType, $mediaType->getType());
        $this->assertSame($expectedSubtype, $mediaType->getSubtype());
        $this->assertSame($expectedParams, $mediaType->getParams());

        $this->assertEquals($expectedString, (string)$mediaType);
    }

    public function newFromFilenameProvider()
    {
        return [
            'txt' => [
                dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'alcamo' . DIRECTORY_SEPARATOR . 'baz.txt',
                'text',
                'plain',
                [ 'charset' => 'us-ascii' ],
                'text/plain; charset="us-ascii"'
            ],
            'json' => [
                dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'alcamo' . DIRECTORY_SEPARATOR . 'foo.json',
                'application',
                'json',
                [],
                'application/json'
            ],
            'css' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo.css',
                'text',
                'css',
                [ 'charset' => 'us-ascii' ],
                'text/css; charset="us-ascii"'
            ],
            'js' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo.js',
                'application',
                'javascript',
                [],
                'application/javascript'
            ],
            'jpeg' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo-16.jpeg',
                'image',
                'jpeg',
                [],
                'image/jpeg'
            ],
            'png' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo-16.png',
                'image',
                'png',
                [],
                'image/png'
            ],
            'svg' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo.svg',
                'image',
                'svg+xml',
                [],
                'image/svg+xml'
            ],
            'svg' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'alcamo.ico',
                'image',
                'vnd.microsoft.icon',
                [],
                'image/vnd.microsoft.icon'
            ]
        ];
    }

    public function testNewFromFilenameException()
    {
        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('File "none.json" not found');

        MediaType::newFromFilename('none.json');
    }
}
