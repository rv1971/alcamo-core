<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class OlTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $content,
        $attrs,
        $expectedString
    ) {
        $ol = new Ol($content, $attrs);

        $this->assertSame('ol', $ol->getTagName());

        $this->assertInstanceOf(TokenList::class, $ol['class']);

        $this->assertSame(count($content), count($ol->getContent()));

        $this->assertEquals($expectedString, (string)$ol);
    }

    public function basicsProvider()
    {
        return [
        'empty' => [ [], null, '<ol></ol>' ],

        'array' => [
        [ 'FOO', new Li(42), new B('BAR') ],
        null,
        '<ol><li>FOO</li><li>42</li><li><b>BAR</b></li></ol>',
        ],

        'Map' => [
        new Map([
          43,
          new Script(null, [ 'src' => 'foo.js' ]),
          new Template('Lorem ipsum'),
          new Li('baz', [ 'id' => 'qux' ])
        ]),
        [ 'class' => 'important' ],
        '<ol class="important"><li>43</li><script src="foo.js"></script>'
        . '<template>Lorem ipsum</template><li id="qux">baz</li></ol>',
        ],
        ];
    }
}
