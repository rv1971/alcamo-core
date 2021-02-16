<?php

namespace alcamo\ietf;

use PHPUnit\Framework\TestCase;
use alcamo\dom\Document;
use alcamo\exception\SyntaxError;
use alcamo\xml\exception\UnknownNamespacePrefix;

class UriTest extends TestCase
{
    public static $context;

    public static function setUpBeforeClass(): void
    {
        self::$context =
            Document::newFromUrl('file://' . dirname(__DIR__) . '/dom/foo.xml')
            ->documentElement;
    }

    /**
     * @dataProvider newFromFilesystemPathProvider
     */
    public function testNewFromFilesystemPath(
        $path,
        $prependScheme,
        $osFamily,
        $expectedUri
    ) {
        $uri = Uri::newFromFilesystemPath($path, $prependScheme, $osFamily);

        $this->assertEquals($expectedUri, (string)$uri);
    }

    public function newFromFilesystemPathProvider()
    {
        return [
            'relative' => [
                'foo/bar', false, 'Linux', 'foo/bar'
            ],
            'absolute' => [
                '/foo/bar/baz', false, 'Linux', '/foo/bar/baz'
            ],
            'absolute-with-scheme' => [
                '/foo/bar/baz', true, 'Linux', 'file:///foo/bar/baz'
            ],
            'win-relative' => [
                'foo\\bar', false, 'Windows', 'foo/bar'
            ],
            'win-absolute' => [
                '\\foo\\bar\\baz', false, 'Windows', '/foo/bar/baz'
            ],
            'win-absolute-with-drive' => [
                'c:\\foo\\bar\\baz', false, 'Windows', '/c:/foo/bar/baz'
            ],
            'win-absolute-with-drive-and-scheme' => [
                'c:\\foo\\bar\\baz', true, 'Windows', 'file:///c:/foo/bar/baz'
            ],
        ];
    }

    /**
     * @dataProvider newFromUriOrSafeCurieAndMapProvider
     */
    public function testNewFromUriOrSafeCurieAndMap(
        $uriOrSafeCurie,
        $map,
        $defaultPrefixValue,
        $expectedUri
    ) {
        $uri = Uri::newFromUriOrSafeCurieAndMap(
            $uriOrSafeCurie,
            $map,
            $defaultPrefixValue
        );

        $this->assertEquals($expectedUri, (string)$uri);
    }

    public function newFromUriOrSafeCurieAndMapProvider()
    {
        $map = [
            'foo' => 'http://foo.example.org/',
            'bar' => 'http://bar.example.org'
        ];

        return [
            'uri' => [
                'http://baz.example.com',
                $map,
                null,
                'http://baz.example.com'
            ],
            'curie' => [
                '[foo:quux]',
                $map,
                'http://baz.example.info',
                'http://foo.example.org/quux'
            ],
            'default-with-colon' => [
                '[:?baz=42#QUUX]',
                $map,
                'http://baz.example.info',
                'http://baz.example.info?baz=42#QUUX'
            ],
            'default-without-colon' => [
                '[?baz=43#CORGE]',
                $map,
                'http://baz.example.info',
                'http://baz.example.info?baz=43#CORGE'
            ]
        ];
    }

    /**
     * @dataProvider newFromUriOrSafeCurieAndContextProvider
     */
    public function testNewFromUriOrSafeCurieAndContext(
        $uriOrSafeCurie,
        $defaultPrefixValue,
        $expectedUri
    ) {
        $uri = Uri::newFromUriOrSafeCurieAndContext(
            $uriOrSafeCurie,
            self::$context,
            $defaultPrefixValue
        );

        $this->assertEquals($expectedUri, (string)$uri);
    }

    public function newFromUriOrSafeCurieAndContextProvider()
    {
        return [
            'uri' => [
                'http://baz.example.com',
                null,
                'http://baz.example.com'
            ],
            'curie' => [
                '[qux:#quux]',
                'http://baz.example.info',
                'http://qux.example.org#quux'
            ],
            'default-with-colon' => [
                '[:?baz=42#QUUX]',
                'http://baz.example.info',
                'http://baz.example.info?baz=42#QUUX'
            ],
            'default-without-colon' => [
                '[?baz=43#CORGE]',
                'http://baz.example.info',
                'http://baz.example.info?baz=43#CORGE'
            ]
        ];
    }

    public function testNewFromSafeCurieAndMapSyntaxException1()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "foo]" at 0: "foo]"; safe CURIE must begin with "["'
        );

        Uri::newFromSafeCurieAndMap('foo]', []);
    }

    public function testNewFromSafeCurieAndMapSyntaxException2()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "[foo" at 3: "o"; safe CURIE must end with "]"'
        );

        Uri::newFromSafeCurieAndMap('[foo', []);
    }

    public function testNewFromSafeCurieAndContextSyntaxException1()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "foo]" at 0: "foo]"; safe CURIE must begin with "["'
        );

        Uri::newFromSafeCurieAndContext('foo]', self::$context);
    }

    public function testNewFromSafeCurieAndContextSyntaxException2()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "[foo" at 3: "o"; safe CURIE must end with "]"'
        );

        Uri::newFromSafeCurieAndContext('[foo', self::$context);
    }

    public function testNewFromCurieAndMapPrefixException()
    {
        $this->expectException(UnknownNamespacePrefix::class);
        $this->expectExceptionMessage(
            'Unknown namespace prefix "foofoo"'
        );

        Uri::newFromCurieAndMap('foofoo:foo', []);
    }

    public function testNewFromCurieAndContextPrefixException()
    {
        $this->expectException(UnknownNamespacePrefix::class);
        $this->expectExceptionMessage(
            'Unknown namespace prefix "foofoo"'
        );

        Uri::newFromCurieAndContext('foofoo:foo', self::$context);
    }
}
