<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;

class HeaderCacheControlTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics($value, $expected)
    {
        exec(
            'php '
            . __DIR__ . DIRECTORY_SEPARATOR . "HeaderCacheControlAux.php $value",
            $output
        );

        $this->assertSame($expected, $output[0]);
    }

    public function basicsProvider()
    {
        return [
        'public' => [
        'public', 'public'
        ],
        'private' => [
        'private', 'private'
        ],
        'no-cache' => [
        'no-cache', 'nocache'
        ],
        'foo' => [
        'foo', 'Invalid value "foo", expected one of: "public", "private", "no-cache"'
        ]
        ];
    }
}