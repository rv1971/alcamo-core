<?php

namespace alcamo\path_creation;

use PHPUnit\Framework\TestCase;

class TrivialPathFactoryTest extends TestCase
{
    public function testCreateFromRelativePath()
    {
        $factory = new TrivialPathFactory();

        $this->assertNull($factory->getBasePath());

        $this->assertSame(
            'foo' . DIRECTORY_SEPARATOR . 'bar',
            $factory->createFromRelativePath(
                'foo' . DIRECTORY_SEPARATOR . 'bar'
            )
        );

        $factory = new TrivialPathFactory('root' . DIRECTORY_SEPARATOR);

        $this->assertSame(
            'root' . DIRECTORY_SEPARATOR,
            $factory->getBasePath()
        );

        $this->assertSame(
            'root' . DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar',
            $factory->createFromRelativePath(
                'foo' . DIRECTORY_SEPARATOR . 'bar'
            )
        );

        $factory = new TrivialPathFactory('root2');

        $this->assertSame(
            'root2' . DIRECTORY_SEPARATOR,
            $factory->getBasePath()
        );

        $this->assertSame(
            'root2' . DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'bar',
            $factory->createFromRelativePath(
                'foo' . DIRECTORY_SEPARATOR . 'bar'
            )
        );
    }
}
