<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\exception\FileNotFound;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class ImgTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics($src, $alt, $attrs, $path, $expectedString)
    {
        $img = Img::newFromLocalUrl($src, $alt, $attrs, $path);

        $this->assertSame('img', $img->getTagName());

        $this->assertInstanceOf(TokenList::class, $img['class']);

        $this->assertNull($img->getContent());

        $this->assertEquals($expectedString, (string)$img);
    }

    public function basicsProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $m16 = gmdate('YmdHis', filemtime("${baseDir}alcamo-16.png"));
        $m32 = gmdate('YmdHis', filemtime("${baseDir}alcamo-32.png"));
        $mJson = gmdate('YmdHis', filemtime("${baseDir}alcamo.json"));

        return [
        'typical-use' => [
        "${baseDir}alcamo-16.png",
        '16x16',
        null,
        null,
        "<img src=\"${baseDir}alcamo-16.png?m=$m16\" alt=\"16x16\" width=\"16\" height=\"16\"/>"
        ],
        'different-path' => [
        '/images/alcamo-32.png?foo=bar',
        '32x32',
        [ 'id' => 'foo' ],
        "${baseDir}alcamo-32.png",
        "<img src=\"/images/alcamo-32.png?foo=bar&amp;m=$m32\" alt=\"32x32\" width=\"32\" height=\"32\" id=\"foo\"/>"
        ],
        'no-size' => [
        "${baseDir}alcamo.json",
        'unknown image format',
        [ 'class' => 'special' ],
        null,
        "<img src=\"${baseDir}alcamo.json?m=$mJson\" alt=\"unknown image format\" class=\"special\"/>"
        ]
        ];
    }

    public function testException()
    {
        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('File "foo.jpeg" not found');

        Img::newFromLocalUrl('foo.jpeg', 'FOO');
    }
}
