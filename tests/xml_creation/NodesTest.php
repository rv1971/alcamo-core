<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

/* xmlString() is implicitely tested in ElementTest.php */

class NodesTest extends TestCase
{
    public function testBasics()
    {
        $data1 = [ 'a', 'B' ];
        $data2 = [ 'CC', 'ddd' ];
        $data3 = [ 'eeee', 'fffff' ];

        $allData = array_merge($data1, $data2, $data3);

        $nodes = new Nodes([ $data1, [ $data2, [ $data3 ] ] ]);

        $this->assertEquals($allData, $nodes->getNodes());

        $node = new Element('foo');

        $allData[] = $node;

        $nodes->append($node);

        $this->assertEquals($allData, $nodes->getNodes());
    }
}
