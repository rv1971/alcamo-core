<?php

namespace alcamo\url_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{DirectoryNotFound, FileNotFound};

class DirPatternMapUrlFactoryTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $htdocsDirPattern,
        $htdocsUrl,
        $disablePreferGz,
        $disableAppendMtime,
        $testItems
    ) {
        $factory = new DirPatternMapUrlFactory(
            $htdocsDirPattern,
            $htdocsUrl,
            $disablePreferGz,
            $disableAppendMtime
        );

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

        $svgPath = __DIR__ . DIRECTORY_SEPARATOR . 'alcamo.svg';

        $mSelf = gmdate('YmdHis', filemtime(__FILE__));
        $mBar = gmdate('YmdHis', filemtime($barPath));
        $mBarGz = gmdate('YmdHis', filemtime("$barPath.gz"));
        $mComposer = gmdate('YmdHis', filemtime($composerPath));
        $mSvg = gmdate('YmdHis', filemtime($svgPath));
        $mSvgz = gmdate('YmdHis', filemtime("${svgPath}z"));

        return [
            'without-mtime' => [
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'url*',
                'https://www.example.org/',
                true,
                true,
                [
                    [
                        __FILE__,
                        'https://www.example.org/DirPatternMapUrlFactoryTest.php'
                    ],
                    [
                        $barPath,
                        $barPath
                    ],
                    [
                        $composerPath,
                        '../../composer.json'
                    ]
                ]
            ],
            'with-mtime' => [
                dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'te?ts'
                . DIRECTORY_SEPARATOR . 'url*',
                '/',
                null,
                null,
                [
                    [ __FILE__, "/DirPatternMapUrlFactoryTest.php?m=$mSelf" ],
                    [ $composerPath, "../../composer.json?m=$mComposer" ],
                    [ realpath($svgPath), "/alcamo.svgz?m=$mSvgz" ]
                ]
            ]
        ];
    }
}
