<?php

namespace alcamo\url_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{DirectoryNotFound, FileNotFound};

class DirMapUrlFactoryTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $htdocsDir,
        $htdocsUrl,
        $disablePreferGz,
        $disableAppendMtime,
        $testItems
    ) {
        $factory = new DirMapUrlFactory(
            $htdocsDir,
            $htdocsUrl,
            $disablePreferGz,
            $disableAppendMtime
        );

        $this->assertSame(realpath($htdocsDir), $factory->getHtdocsDir());
        $this->assertSame(rtrim($htdocsUrl, '/'), $factory->getHtdocsUrl());
        $this->assertSame((bool)$disablePreferGz, $factory->getDisablePreferGz());
        $this->assertSame(
            (bool)$disableAppendMtime,
            $factory->getDisableAppendMtime()
        );

        foreach ($testItems as $testItem) {
            [ $path, $expectedHref ] = $testItem;

            $this->assertEquals($expectedHref, $factory->createFromPath($path));
        }
    }

    public function basicsProvider()
    {
        chdir(__DIR__);

        $barPath = dirname(__DIR__) . DIRECTORY_SEPARATOR
            . 'alcamo' . DIRECTORY_SEPARATOR . 'bar.ini';

        $composerPath = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            . 'composer.json';

        $svgPath = __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'html_creation' . DIRECTORY_SEPARATOR
            . 'element' . DIRECTORY_SEPARATOR . 'alcamo.svg';

        $mSelf = gmdate('YmdHis', filemtime(__FILE__));
        $mBar = gmdate('YmdHis', filemtime($barPath));
        $mBarGz = gmdate('YmdHis', filemtime("$barPath.gz"));
        $mComposer = gmdate('YmdHis', filemtime($composerPath));
        $mSvg = gmdate('YmdHis', filemtime($svgPath));
        $mSvgz = gmdate('YmdHis', filemtime("${svgPath}z"));

        return [
            'without-mtime' => [
                dirname(__DIR__) . DIRECTORY_SEPARATOR,
                'https://www.example.org/',
                true,
                true,
                [
                    [
                        __FILE__,
                        'https://www.example.org/url_creation/DirMapUrlFactoryTest.php'
                    ],
                    [
                        $barPath,
                        'https://www.example.org/alcamo/bar.ini'
                    ],
                    [
                        $composerPath,
                        '../../composer.json'
                    ]
                ]
            ],
            'with-mtime' => [
                dirname(dirname(__DIR__)),
                '/',
                null,
                null,
                [
                    [ __FILE__, "/tests/url_creation/DirMapUrlFactoryTest.php?m=$mSelf" ],
                    [ $barPath, "/tests/alcamo/bar.ini.gz?m=$mBarGz" ],
                    [ $composerPath, "/composer.json?m=$mComposer" ],
                    [ $svgPath, "/tests/html_creation/element/alcamo.svgz?m=$mSvgz" ]
                ]
            ]
        ];
    }

    public function testConstructException()
    {
        $this->expectException(DirectoryNotFound::class);
        $this->expectExceptionMessage('Directory "foo/bar" not found');

        new DirMapUrlFactory('foo/bar', '/');
    }

    public function testCreateFromPathException()
    {
        $factory = new DirMapUrlFactory(__DIR__, '/');

        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('File "foofoo.xml" not found');

        $factory->createFromPath('foofoo.xml');
    }
}
