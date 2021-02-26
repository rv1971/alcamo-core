<?php

namespace alcamo\html_creation;

use PHPUnit\Framework\TestCase;

class TrivialRelativeUrlFactoryTest extends TestCase
{
    public function testCreateFromPath()
    {
        $factory = new TrivialRelativeUrlFactory();

        $this->assertSame(
            'foo/bar/baz',
            $factory->createFromPath(
                'foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR . 'baz'
            )
        );
    }
}
