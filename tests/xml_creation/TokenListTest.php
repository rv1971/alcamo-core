<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

/* This also tests IteratorAggregateTrait and DecoratorTrait. */

class TokenListTest extends TestCase
{
    public function testBasics()
    {
        $tokens1 = 'foo bar baz qux';

        $list1 = new TokenList($tokens1);

        $this->assertEquals(4, count($list1));

        $this->assertEquals($tokens1, (string)$list1);

        $list1->remove('foo');

        $list1->remove('baz');

        $list1->add('quux');

        $this->assertEquals('bar qux quux', (string)$list1);

        $dump = '';

        foreach ($list1 as $key => $value) {
            $dump .= " $key=$value";
        }

        $this->assertEquals(' 0=bar 1=qux 2=quux', $dump);

        $tokens2 = [ 'FOO', 'BAR' ];

        $list2 = new TokenList($tokens2);

        $this->assertEquals(2, count($list2));

        $this->assertEquals('FOO BAR', (string)$list2);

        $list2->sort();

        $this->assertEquals('BAR FOO', (string)$list2);
    }
}
