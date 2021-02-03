<?php

namespace alcamo\ietf;

use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
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
}
