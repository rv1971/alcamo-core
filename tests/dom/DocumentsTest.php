<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;
use alcamo\exception\Uninitialized;

class DocumentsTest extends TestCase
{
    public function textConstruct()
    {
        $docs = new Documents([
            'FOO'
            => Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'),
            Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'bar.xml')
        ]);

        $this->assertSame('foo', $docs['FOO']->documentElement->tagName);

        $this->assertSame('bar', $docs['bar-bar']->documentElement->tagName);
    }

    public function testNewFromGlob()
    {
        $docs =
            Documents::newFromGlob(__DIR__ . DIRECTORY_SEPARATOR . '*.xml');

        $this->assertSame('foo', $docs['foo']->documentElement->tagName);

        $this->assertSame('bar', $docs['bar-bar']->documentElement->tagName);
    }
}
