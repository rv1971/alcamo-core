<?php

namespace alcamo\html_creation;

use PHPUnit\Framework\TestCase;
use alcamo\html_creation\element\{Icon, Link, Script, Stylesheet};

class ResourceFactoryTest extends TestCase
{
  /**
   * @dataProvider createElementsFromItemsProvider
   */
    public function testCreateElementsFromItems(
        $urlFactory,
        $items,
        $expectedClasses,
        $expectedString
    ) {
        $factory = new ResourceFactory($urlFactory);
        $nodes = $factory->createElementsFromItems($items);

        $this->assertSame(count($items), count($nodes));

        $i = 0;

        foreach ($nodes as $node) {
            $this->assertInstanceOf($expectedClasses[$i++], $node);
        }

        $this->assertEquals($expectedString, (string)$nodes);
    }

    public function createElementsFromItemsProvider()
    {
        $cssPath = __DIR__ . DIRECTORY_SEPARATOR
        . 'element' . DIRECTORY_SEPARATOR . 'alcamo.css';

        $jsonPath = __DIR__ . DIRECTORY_SEPARATOR
        . 'element' . DIRECTORY_SEPARATOR . 'alcamo.json';

        $jsPath = __DIR__ . DIRECTORY_SEPARATOR
        . 'element' . DIRECTORY_SEPARATOR . 'alcamo.js';

        $png16Path = __DIR__ . DIRECTORY_SEPARATOR
        . 'element' . DIRECTORY_SEPARATOR . 'alcamo-16.png';

        $svgPath = __DIR__ . DIRECTORY_SEPARATOR
        . 'element' . DIRECTORY_SEPARATOR . 'alcamo.svg';

        $mCss = gmdate('YmdHis', filemtime($cssPath));

        $mCssGz = gmdate('YmdHis', filemtime("$cssPath.gz"));

        $mJson = gmdate('YmdHis', filemtime($jsonPath));

        $mJs = gmdate('YmdHis', filemtime($jsPath));

        $mJsGz = gmdate('YmdHis', filemtime("$jsPath.gz"));

        $mPng16 = gmdate('YmdHis', filemtime($png16Path));

        $mSvg = gmdate('YmdHis', filemtime($svgPath));

        $mSvgz = gmdate('YmdHis', filemtime("${svgPath}z"));

        return [
        'simple' => [
        new DirMapUrlFactory(__DIR__, '/test/', true, true),
        [
          $cssPath,
          [ $jsonPath, 'manifest' ],
          $jsPath,
          $png16Path,
          $svgPath
        ],
        [
          Stylesheet::class,
          Link::class,
          Script::class,
          Icon::class,
          Icon::class
        ],
        "<link rel=\"stylesheet\" href=\"/test/element/alcamo.css?m=$mCss\"/>"
        . "<link rel=\"manifest\" href=\"/test/element/alcamo.json?m=$mJson\" type=\"application/json\"/>"
        . "<script src=\"/test/element/alcamo.js?m=$mJs\"></script>"
        . "<link rel=\"icon\" href=\"/test/element/alcamo-16.png?m=$mPng16\" type=\"image/png\" sizes=\"16x16\"/>"
        . "<link rel=\"icon\" href=\"/test/element/alcamo.svg?m=$mSvg\" type=\"image/svg+xml\" sizes=\"any\"/>"
        ],
        'gz-with-attrs' => [
        new DirMapUrlFactory(__DIR__, '/test/'),
        [
          [ $jsPath, [ 'id' => 'JS' ] ],
          $cssPath,
          [ $jsonPath, [ 'rel' => 'dc:relation' ] ],
          $png16Path,
          $svgPath
        ],
        [
          Script::class,
          Stylesheet::class,
          Link::class,
          Icon::class,
          Icon::class
        ],
        "<script src=\"/test/element/alcamo.js.gz?m=$mJsGz\" id=\"JS\"></script>"
        . "<link rel=\"stylesheet\" href=\"/test/element/alcamo.css.gz?m=$mCssGz\"/>"
        . "<link rel=\"dc:relation\" href=\"/test/element/alcamo.json?m=$mJson\" type=\"application/json\"/>"
        . "<link rel=\"icon\" href=\"/test/element/alcamo-16.png?m=$mPng16\" type=\"image/png\" sizes=\"16x16\"/>"
        . "<link rel=\"icon\" href=\"/test/element/alcamo.svgz?m=$mSvgz\" type=\"image/svg+xml\" sizes=\"any\"/>"
        ]
        ];
    }
}
