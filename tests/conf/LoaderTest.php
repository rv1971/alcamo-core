<?php

namespace alcamo\conf;

use PHPUnit\Framework\TestCase;

use alcamo\exception\FileNotFound;

class LoaderTest extends TestCase {
  public function testload() {
    $configHome = dirname( __DIR__ );

    putenv( "XDG_CONFIG_HOME=$configHome" );

    $loader = new Loader();

    $data1 = $loader->load( [ 'bar.ini', 'foo.json' ] );

    $this->assertSame(
      $data1,
      [
        'quux' => 45,
        'corge' => 'foo bar baz',
        'bar' => 46,
        'baz' => 'Stet clita kasd gubergren',
        'qux' => true
      ]
    );
  }

  public function testFileNotFound() {
    $configHome = dirname( __DIR__ );

    $this->expectException( FileNotFound::class );
    $this->expectExceptionMessage(
      "File 'foo.ini' not found in '$configHome:" . __DIR__ . "'" );

    putenv( "XDG_CONFIG_HOME=$configHome" );
    putenv( "XDG_CONFIG_DIRS=" . __DIR__ );

    $loader = new Loader();

    $loader->load( 'foo.ini' );
  }
}
