<?php

namespace alcamo\url_creation;

use PHPUnit\Framework\TestCase;

class TrivialUrlFactoryTest extends TestCase
{
    public function testCreateFromPath()
    {
        $factory = new TrivialUrlFactory();

        $this->assertNull($factory->getBaseUrl());

        $this->assertSame(
            'foo/bar/baz',
            $factory->createFromPath(
                'foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR . 'baz'
            )
        );

        $factory = new TrivialUrlFactory('https://www.example.org/');

        $this->assertSame(
            'https://www.example.org/',
            $factory->getBaseUrl()
        );

        $this->assertSame(
            'https://www.example.org/foo/bar/baz',
            $factory->createFromPath(
                'foo' . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR . 'baz'
            )
        );
    }
}
