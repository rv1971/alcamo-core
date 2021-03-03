<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class FileLocationTest extends TestCase
{
    /**
     * @dataProvider newFromThrowableProvider
     */
    public function testNewFromThrowable(
        $throwable,
        $expectedFilename,
        $expectedLine,
        $expectedString
    ) {
        $location = FileLocation::newFromThrowable($throwable);

        $this->assertSame($expectedFilename, $location->getFilename());
        $this->assertSame($expectedLine, $location->getLine());
        $this->assertNull($location->getColumn());
        $this->assertSame($expectedString, (string)$location);
    }

    public function newFromThrowableProvider()
    {
        try {
            throw new \Exception();
        } catch (\Exception $e) {
            return [
                [
                    $e,
                    __FILE__,
                    29,
                    __FILE__ . ':29'
                ]
            ];
        }
    }

    /**
     * @dataProvider newFromBacktraceItemProvider
     */
    public function testNewFromBacktraceItem(
        $backtraceItem,
        $expectedFilename,
        $expectedLine,
        $expectedString
    ) {
        $location = FileLocation::newFromBacktraceItem($backtraceItem);

        $this->assertSame($expectedFilename, $location->getFilename());
        $this->assertSame($expectedLine, $location->getLine());
        $this->assertNull($location->getColumn());
        $this->assertSame($expectedString, (string)$location);
    }

    public function newFromBacktraceItemProvider()
    {
        return [
            'filename-line' => [
                [ 'file' => 'foo.php', 'line' => 42 ],
                'foo.php',
                42,
                'foo.php:42'
            ],
            'filename-only' => [
                [ 'file' => 'bar.php' ],
                'bar.php',
                null,
                'bar.php'
            ],
            'line-only' => [
                [ 'line' => 42 ],
                null,
                42,
                '42'
            ],
            'empty' => [
                [],
                null,
                null,
                ''
            ]
        ];
    }

    /**
     * @dataProvider stringProvider
     */
    public function testString($filename, $line, $column, $expectedString)
    {
        $location = new FileLocation($filename, $line, $column);

        $this->assertSame($filename, $location->getFilename());
        $this->assertSame($line, $location->getLine());
        $this->assertSame($column, $location->getColumn());
        $this->assertSame($expectedString, (string)$location);
    }

    public function stringProvider()
    {
        return [
            'filename-line-column' => [ 'foo.xml', 42, 43, 'foo.xml:42:43' ],
            'line-column' => [ null, 42, 43, '42:43' ],
            'line' => [ null, 42, null, '42' ],
            'columne' => [ null, null, 43, 'column 43' ],
        ];
    }
}
