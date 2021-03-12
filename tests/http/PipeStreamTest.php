<?php

namespace alcamo\http;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{Closed, PopenFailed};

class PipeStreamTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics($command, $expectedOutput)
    {
        $stream = new PipeStream($command, "rt");

        $this->assertNull($stream->getStatus());

        $this->expectOutputString($expectedOutput);

        $count = $stream->emit();

        $this->assertSame(strlen($expectedOutput), $count);

        $stream->close();

        $this->assertIsInt($stream->getStatus());
    }

    public function basicsProvider()
    {
        return [
            [ 'echo foo', "foo\n" ],
            [ 'echo bar baz qux', "bar baz qux\n" ],
        ];
    }

    public function testEmitException()
    {
        $stream = new PipeStream('echo', 'rt');

        $content = (string)$stream;

        $stream->close();

        $this->expectException(Closed::class);
        $this->expectExceptionMessage(
            'Attempt to use closed ' . PipeStream::class
        );

        $stream->emit();
    }
}
