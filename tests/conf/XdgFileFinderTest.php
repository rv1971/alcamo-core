<?php

namespace alcamo\conf;

use PHPUnit\Framework\TestCase;

use alcamo\exception\InvalidEnumerator;

class XdgFileFinderTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $subdir, $type, $filename, $expectedPathname
  ) {
    $finder = new XdgFileFinder( $subdir, $type );

    $this->assertSame( $finder->getSubdir(), $subdir ?? 'alcamo' );

    $pathname = $finder->find( $filename );

    $this->assertSame( $pathname, $expectedPathname );

  }

  public function allProvider() : array {
    $configHome = dirname( __DIR__ );
    $dataHome1 = __DIR__;
    $dataHome2 = dirname( $configHome ) . DIRECTORY_SEPARATOR . 'src';

    putenv( "XDG_CONFIG_HOME=$configHome" );
    putenv( "XDG_DATA_DIRS=$dataHome1:$dataHome2" );

    return [
      'typical-use' => [
        null,
        null,
        'foo.json',
        $configHome . DIRECTORY_SEPARATOR
        . 'alcamo' . DIRECTORY_SEPARATOR . 'foo.json'
      ],

      'custom-subdir' => [
        'conf',
        null,
        'XdgFileFinderTest.php',
        __FILE__
      ],

      'data-file' => [
        'conf',
        'DATA',
        'XdgFileFinder.php',
        $dataHome2 . DIRECTORY_SEPARATOR
        . 'conf' . DIRECTORY_SEPARATOR . 'XdgFileFinder.php'
      ]
    ];
  }

  public function testException() {
    $this->expectException( InvalidEnumerator::class );
    $this->expectExceptionMessage(
      "Invalid value 'FOO', expected one of: 'CONFIG', 'DATA'" );

    new XdgFileFinder( null, 'FOO' );
  }
}
