<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class IconTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $icon = new Icon($href, $attrs);

        $this->assertSame('link', $icon->getTagName());

        $this->assertInstanceOf(TokenList::class, $icon['class']);

        $this->assertSame('icon', $icon['rel']);

        $this->assertSame($href, $icon['href']);

        $this->assertNull($icon->getContent());

        $this->assertEquals($expectedString, (string)$icon);
    }

    public function basicsProvider()
    {
        return [
        'typical-use' => [
        'quux.png',
        [ 'type' => 'image/png' ],
        '<link rel="icon" href="quux.png" type="image/png"/>'
        ]
        ];
    }

  /**
   * @dataProvider newFromLocalUrlProvider
   */
    public function testNewFromLocalUrl(
        $href,
        $attrs,
        $path,
        $expectedString
    ) {
        $icon = Icon::newFromLocalUrl($href, $attrs, $path);

        $this->assertSame('link', $icon->getTagName());

        $this->assertInstanceOf(TokenList::class, $icon['class']);

        $this->assertSame($attrs['rel'] ?? 'icon', $icon['rel']);

        $this->assertNull($icon->getContent());

        $this->assertEquals($expectedString, (string)$icon);
    }

    public function newFromLocalUrlProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $m16 = gmdate('YmdHis', filemtime("${baseDir}alcamo-16.png"));
        $m32 = gmdate('YmdHis', filemtime("${baseDir}alcamo-32.png"));
        $m64 = gmdate('YmdHis', filemtime("${baseDir}alcamo-64.png"));
        $mJpeg = gmdate('YmdHis', filemtime("${baseDir}alcamo-16.jpeg"));
        $mSvg = gmdate('YmdHis', filemtime("${baseDir}alcamo.svg"));
        $mIco = gmdate('YmdHis', filemtime("${baseDir}alcamo.ico"));

        return [
        'png16' => [
        "${baseDir}alcamo-16.png",
        null,
        null,
        "<link rel=\"icon\" href=\"${baseDir}alcamo-16.png?m=$m16\" type=\"image/png\" sizes=\"16x16\"/>"
        ],

        'png32' => [
        "${baseDir}alcamo-32.png",
        [ 'id' => 'BAZ' ],
        null,
        "<link rel=\"icon\" href=\"${baseDir}alcamo-32.png?m=$m32\" type=\"image/png\" sizes=\"32x32\" id=\"BAZ\"/>"
        ],

        'jpeg' => [
        "/icons/alcamo-16.jpeg",
        [ 'rel' => 'apple-touch-icon' ],
        "${baseDir}alcamo-16.jpeg",
        "<link rel=\"apple-touch-icon\" href=\"/icons/alcamo-16.jpeg?m=$mJpeg\" type=\"image/jpeg\" sizes=\"16x16\"/>"
        ],

        'svg' => [
        "${baseDir}alcamo.svg",
        null,
        null,
        "<link rel=\"icon\" href=\"${baseDir}alcamo.svg?m=$mSvg\" type=\"image/svg+xml\" sizes=\"any\"/>"
        ],

        'ico' => [
        "${baseDir}alcamo.ico",
        null,
        null,
        "<link rel=\"icon\" href=\"${baseDir}alcamo.ico?m=$mIco\" type=\"image/vnd.microsoft.icon\" sizes=\"64x64\"/>"
        ],

        'explicit-type' => [
        "${baseDir}alcamo-64.png",
        [ 'type' => 'image/x-foo' ],
        null,
        "<link rel=\"icon\" href=\"${baseDir}alcamo-64.png?m=$m64\" type=\"image/x-foo\" sizes=\"64x64\"/>"
        ],


        ];
    }
}
