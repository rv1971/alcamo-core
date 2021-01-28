<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

class RawTest extends TestCase
{
    public function testBasics()
    {
        $text =
        '<strong>At vero eos et accusam</strong> et justo duo dolores et ea rebum.';

        $raw = new Raw($text);

        $this->assertSame($text, $raw->getContent());

        $this->assertEquals($text, (string)$raw);
    }
}
