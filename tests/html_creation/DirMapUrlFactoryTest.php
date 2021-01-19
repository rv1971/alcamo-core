<?php

namespace alcamo\html_creation;

use PHPUnit\Framework\TestCase;

use alcamo\exception\{DirectoryNotFound, FileNotFound};

class DirMapUrlFactoryTest extends TestCase {
  /**
   * @dataProvider basicsProvider
   */
  public function testBasics(
    $htdocsDir, $htdocsUrl, $appendMtime, $testItems
  ) {
    $factory = new DirMapUrlFactory( $htdocsDir, $htdocsUrl, $appendMtime );

    $this->assertSame( realpath( $htdocsDir ), $factory->getHtdocsDir() );
    $this->assertSame( rtrim( $htdocsUrl, '/' ), $factory->getHtdocsUrl() );
    $this->assertSame( (bool)$appendMtime, $factory->getAppendMtime() );

    foreach ( $testItems as $testItem ) {
      [ $path, $expectedHref ] = $testItem;

      $this->assertEquals( $expectedHref, $factory->createFromPath( $path ) );
    }
  }

  public function basicsProvider() {
    chdir( __DIR__ );

    $barPath = dirname( __DIR__ ) . DIRECTORY_SEPARATOR
      . 'alcamo' . DIRECTORY_SEPARATOR . 'bar.ini';

    $composerPath = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
      . 'composer.json';

    $mSelf = gmdate( 'YmdHis', filemtime( __FILE__ ) );
    $mBar = gmdate( 'YmdHis', filemtime( $barPath ) );
    $mComposer = gmdate( 'YmdHis', filemtime( $composerPath ) );

    return [
      'without-mtime' => [
        dirname( __DIR__ ) . DIRECTORY_SEPARATOR,
        'https://www.example.org/',
        null,
        [
          [
            __FILE__,
            'https://www.example.org/html_creation/DirMapUrlFactoryTest.php'
          ],
          [
            $barPath,
            'https://www.example.org/alcamo/bar.ini'
          ],
          [
            $composerPath,
            '../../composer.json'
          ]
        ]
      ],
      'with-mtime' => [
        dirname( dirname( __DIR__ ) ),
        '/',
        true,
        [
          [ __FILE__, "/tests/html_creation/DirMapUrlFactoryTest.php?m=$mSelf" ],
          [ $barPath, "/tests/alcamo/bar.ini?m=$mBar" ],
          [ $composerPath, "/composer.json?m=$mComposer" ]
        ]
      ]
    ];
  }

  public function testConstructException() {
    $this->expectException( DirectoryNotFound::class );
    $this->expectExceptionMessage( 'Directory "foo/bar" not found' );

    new DirMapUrlFactory( 'foo/bar', '/' );
  }

  public function testCreateFromPathException() {
    $factory = new DirMapUrlFactory( __DIR__, '/' );

    $this->expectException( FileNotFound::class );
    $this->expectExceptionMessage( 'File "foo.xml" not found' );

    $factory->createFromPath( 'foo.xml' );
  }
}
