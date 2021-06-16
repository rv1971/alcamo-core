<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class LinkTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $link = new Link($href, $attrs);

        $this->assertSame('link', $link->getTagName());

        $this->assertInstanceOf(TokenList::class, $link['class']);

        $this->assertSame($href, $link['href']);

        $this->assertNull($link->getContent());

        $this->assertEquals($expectedString, (string)$link);
    }

    public function basicsProvider()
    {
        return [
            'typical-use' => [
                'foo.css',
                [ 'rel' => 'stylesheet', 'type' => 'text/css' ],
                '<link rel="stylesheet" type="text/css" href="foo.css"/>'
            ],

            'override-attrs' => [
                'baz.php',
                [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
                '<link href="baz.php" rel="dc:source"/>'
            ]
        ];
    }

  /**
   * @dataProvider newFromRelAndLocalUrlProvider
   */
    public function testNewFromLocalUrl(
        $href,
        $attrs,
        $path,
        $expectedString
    ) {
        $link = Link::newFromLocalUrl($href, $attrs, $path);

        $this->assertSame('link', $link->getTagName());

        $this->assertInstanceOf(TokenList::class, $link['class']);

        $this->assertNull($link->getContent());

        $this->assertEquals($expectedString, (string)$link);
    }

    public function newFromRelAndLocalUrlProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $mCss = gmdate('YmdHis', filemtime("${baseDir}alcamo.css"));

        $baseDir2 = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
        . 'alcamo' . DIRECTORY_SEPARATOR;

        $mJson = gmdate('YmdHis', filemtime("${baseDir2}foo.json"));

        return [
            'css' => [
                "${baseDir}alcamo.css",
                [ 'rel' => 'stylesheet', 'disable' => true ],
                null,
                "<link type=\"text/css; charset=&quot;us-ascii&quot;\" "
                . "rel=\"stylesheet\" disable=\"disable\" "
                . "href=\"${baseDir}alcamo.css?m=$mCss\"/>"
            ],
            'json' => [
                "/foo.json?baz=qux",
                [ 'id' => 'FOO', 'rel' => 'manifest' ],
                "${baseDir2}foo.json",
                "<link type=\"application/json\" id=\"FOO\" rel=\"manifest\" "
                . "href=\"/foo.json?baz=qux&amp;m=$mJson\"/>"
            ],
            'explicit-type-and-modtime' => [
                "/foo.json?m=19700101000000",
                [ 'rel' => 'dc:isVersionOf', 'type' => 'application/x-quux' ],
                "${baseDir2}foo.json",
                "<link rel=\"dc:isVersionOf\" type=\"application/x-quux\" "
                . "href=\"/foo.json?m=19700101000000\"/>"
            ],
            'explicit-modtime-2' => [
                "/foo.json?bar=foo&m=19700101000000",
                [ 'rel' => 'dc:isPartOf' ],
                "${baseDir2}foo.json",
                "<link type=\"application/json\" rel=\"dc:isPartOf\" "
                . "href=\"/foo.json?bar=foo&amp;m=19700101000000\"/>"
            ]
        ];
    }
}
