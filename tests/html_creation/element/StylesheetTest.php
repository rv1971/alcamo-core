<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class StylesheetTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $attrs,
        $expectedString
    ) {
        $stylesheet = new Stylesheet($href, $attrs);

        $this->assertSame('link', $stylesheet->getTagName());

        $this->assertInstanceOf(TokenList::class, $stylesheet['class']);

        $this->assertSame('stylesheet', $stylesheet['rel']);

        $this->assertSame($href, $stylesheet['href']);

        $this->assertNull($stylesheet->getContent());

        $this->assertEquals($expectedString, (string)$stylesheet);
    }

    public function basicsProvider()
    {
        return [
            'typical-use' => [
                'foo.css',
                null,
                '<link rel="stylesheet" href="foo.css"/>'
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
        $stylesheet = Stylesheet::newFromLocalUrl($href, $attrs, $path);

        $this->assertSame('link', $stylesheet->getTagName());

        $this->assertInstanceOf(TokenList::class, $stylesheet['class']);

        $this->assertSame('stylesheet', $stylesheet['rel']);

        $this->assertNull($stylesheet->getContent());

        $this->assertEquals($expectedString, (string)$stylesheet);
    }

    public function newFromLocalUrlProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $mCss = gmdate('YmdHis', filemtime("${baseDir}alcamo.css"));

        return [
            'css' => [
                "${baseDir}alcamo.css",
                [ 'disable' => true ],
                null,
                "<link disable=\"disable\" rel=\"stylesheet\" href=\"${baseDir}alcamo.css?m=$mCss\"/>"
            ]
        ];
    }
}
