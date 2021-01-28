<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class TrTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $content,
        $attrs,
        $cellClass,
        $expectedString
    ) {
        $tr = new Tr($content, $attrs, $cellClass);

        $this->assertSame('tr', $tr->getTagName());

        $this->assertInstanceOf(TokenList::class, $tr['class']);

        $this->assertSame(count($content), count($tr->getContent()));

        $this->assertEquals($expectedString, (string)$tr);
    }

    public function basicsProvider()
    {
        return [
        'empty' => [ [], null, null, '<tr></tr>' ],

        'array' => [
        [
          'FOO',
          new Td(42),
          new B('BAR'),
          new Th(new I('QUX'))
        ],
        null,
        null,
        '<tr><td>FOO</td><td>42</td><td><b>BAR</b></td><th><i>QUX</i></th></tr>',
        ],

        'Map' => [
        new Map([
          43,
          new Script(null, [ 'src' => 'foo.js' ]),
          new Th([ 'At vero eos', ' ' ]),
          new Template('Lorem ipsum'),
          new Td('baz', [ 'id' => 'qux' ])
        ]),
        [ 'class' => 'important' ],
        Th::class,
        '<tr class="important"><th>43</th><script src="foo.js"></script>'
        . '<th>At vero eos </th><template>Lorem ipsum</template><td id="qux">baz</td></tr>',
        ],
        ];
    }
}
