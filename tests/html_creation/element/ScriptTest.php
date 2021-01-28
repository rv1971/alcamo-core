<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\exception\FileNotFound;
use alcamo\xml_creation\TokenList;

class ScriptTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $content,
        $attrs,
        $expectedString
    ) {
        $script = new Script($content, $attrs);

        $this->assertSame('script', $script->getTagName());

        $this->assertInstanceOf(TokenList::class, $script['class']);

        $this->assertSame($content ?? '', $script->getContent());

        $this->assertEquals($expectedString, (string)$script);
    }

    public function basicsProvider()
    {
        return [
        'typcial-use' => [
        null,
        [ 'href' => 'foo.js' ],
        '<script href="foo.js"></script>'
        ],

        'with-content' => [
        'alert( "Hello World!" );',
        null,
        '<script>alert( "Hello World!" );</script>'
        ],

        'with-json-content' => [
        '{ "foo": "bar" }',
        [ 'type' => 'application/json' ],
        '<script type="application/json">{ "foo": "bar" }</script>'
        ]
        ];
    }

  /**
   * @dataProvider newFromLocalUrlProvider
   */
    public function testNewFromLocalUrl($src, $attrs, $path, $expectedString)
    {
        $script = Script::newFromLocalUrl($src, $attrs, $path);

        $this->assertSame('script', $script->getTagName());

        $this->assertInstanceOf(TokenList::class, $script['class']);

        $this->assertEquals('', $script->getContent());

        $this->assertEquals($expectedString, (string)$script);
    }

    public function newFromLocalUrlProvider()
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

        $m = gmdate('YmdHis', filemtime("${baseDir}alcamo.js"));

        return [
        'typical-use' => [
        "${baseDir}alcamo.js?baz=qux",
        [ 'id' => 'js' ],
        null,
        "<script src=\"${baseDir}alcamo.js?baz=qux&amp;m=$m\" id=\"js\"></script>"
        ],
        'different-path' => [
        '/scripts/alcamo.js',
        null,
        "${baseDir}alcamo.js",
        "<script src=\"/scripts/alcamo.js?m=$m\"></script>"
        ]
        ];
    }

    public function testException()
    {
        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('File "foo.js" not found');

        Script::newFromLocalUrl('foo.js');
    }
}
