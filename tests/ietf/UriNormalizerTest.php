<?php

namespace alcamo\ietf;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\UriNormalizer as GuzzleHttpUriNormalizer;

class UriNormalizerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (PHP_OS_FAMILY != 'Windows') {
            system(
                'ln -s '
                . dirname(__DIR__) . ' '
                . __DIR__ . '/foobar'
            );
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (PHP_OS_FAMILY != 'Windows') {
            system('rm ' . __DIR__ . '/foobar');
        }
    }

    /**
     * @dataProvider normalizeProvider
     */
    public function testNormalize(
        $uri,
        $flags,
        $osFamily,
        $expectedUri
    ) {
        $normalizedUri =
            UriNormalizer::normalize(new Uri($uri), $flags, $osFamily);

        $this->assertEquals(
            $expectedUri ?? (string)$uri,
            (string)$normalizedUri
        );
    }

    public function normalizeProvider()
    {
        $testCases = [
            'no-realpath' => [
                'file:///foo/bar/baz/qux',
                GuzzleHttpUriNormalizer::PRESERVING_NORMALIZATIONS,
                null,
                null
            ],
            'no-scheme' => [
                '/foo/bar/baz/qux', null, 'Linux', '/foo/bar/baz/qux'
            ],
            'not-local' => [
                'file://foo.example.org/bar/baz/../qux',
                null,
                null,
                'file://foo.example.org/bar/qux',
            ],
            'relative' => [
                'foo/bar/baz', null, null, 'foo/bar/baz'
            ],
            'windows' => [
                'file:/c:/foo/bar/baz', null, 'Windows', 'file:///c:/foo/bar/baz'
            ]
        ];

        if (PHP_OS_FAMILY != 'Windows') {
            $testCases['realpath'] = [
                'file://' . __DIR__ . '/foobar/ietf/UriNormalizerTest.php',
                null,
                'Linux',
                'file://' . __FILE__,
            ];
        }

        return $testCases;
    }
}
