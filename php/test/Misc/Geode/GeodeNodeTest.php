<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeodeNodeTest
 *
 * @author thiba_000
 */

use \Misc\Geode\Geode ;
use \Misc\Geode\GeodeNode ;

class GeodeNodeTest extends PHPUnit_Framework_TestCase {
    
    public function test_init() {
        $geode = new Geode(10) ;
        
        $this->assertEquals($geode->getSize(), 10) ;
        
        return $geode ;
    }
    
    /**
     * @depends test_init
     */
    function test_construct(Geode $g) {

        $point = new GeodeNode($g, 1, 2, 3, 4, 5) ;
        
        
        $this->assertEquals($point->getA(), 1) ;
        $this->assertEquals($point->getB(), 2) ;
        $this->assertEquals($point->getC(), 3) ;
        $this->assertEquals($point->getI(), 4) ;
        $this->assertEquals($point->getJ(), 5) ;
        
        return $point ;
    }
    
    /**
     * @depends test_init
     */
    function test_equals($g) {
        
        $gn1 = new GeodeNode($g, 1, 2, 3, 4, 5) ;
        
        $gn2 = new GeodeNode($g, 1, 2, 3, 4, 5) ;
        $this->assertTrue($gn1->equals($gn2)) ;
        
        $gn3 = new GeodeNode($g, 0, 2, 3, 4, 5) ;
        $this->assertFalse($gn1->equals($gn3)) ;
        
        $gn4 = new GeodeNode($g, 1, 0, 3, 4, 5) ;
        $this->assertFalse($gn1->equals($gn4)) ;
        
        $gn5 = new GeodeNode($g, 1, 2, 0, 4, 5) ;
        $this->assertFalse($gn1->equals($gn5)) ;
        
        $gn6 = new GeodeNode($g, 1, 2, 3, 0, 5) ;
        $this->assertFalse($gn1->equals($gn6)) ;
        
        $gn7 = new GeodeNode($g, 1, 2, 3, 4, 0) ;
        $this->assertFalse($gn1->equals($gn7)) ;
        
    }
    
    /**
     * @depends test_init
     * @dataProvider normalizeProvider
     */
    public function test_normalize_($g, $con, $prod) {
        $point = new GeodeNode($g, $con[0], $con[1], $con[2], $con[3], $con[4]) ;
        
        $this->assertEquals($point->getA(), $prod[0]) ;
        $this->assertEquals($point->getB(), $prod[1]) ;
        $this->assertEquals($point->getC(), $prod[2]) ;
        $this->assertEquals($point->getI(), $prod[3]) ;
        $this->assertEquals($point->getJ(), $prod[4]) ;
        
        return $point ;
    }
    
    public function normalizeProvider() {
        $g = new Geode(10) ;
        return array(
            array($g, array(1, 2, 3, 4, 5), array(1, 2, 3, 4, 5)), // 0 -- nothing to do
            array($g, array(1, 2, 3, 4, 0), array(1, 2, 0, 4, 0)), // 1 -- j = 0
            array($g, array(1, 2, 3, 0, 0), array(1, 0, 0, 0, 0)), // 2 -- i = 0 & j = 0
            array($g, array(1, 2, 3, 0, 5), array(1, 3, 0, 5, 0)), // 3 -- i = 0
            array($g, array(1, 2, 3, 2, 8), array(2, 3, 0, 8, 0)), // 4 -- i + j = size
            array($g, array(2, 1, 3, 4, 5), array(1, 2, 3, 1, 5)), // 5 -- sigma12
            array($g, array(1, 3, 2, 4, 5), array(1, 2, 3, 5, 4)), // 6 -- sigma23
            array($g, array(3, 2, 1, 2, 3), array(1, 2, 3, 2, 5)), // 7 -- sigma13
            array($g, array(2, 3, 1, 2, 3), array(1, 2, 3, 5, 2)), // 8 -- sigma13 puis sigma23
            array($g, array(3, 1, 2, 2, 3), array(1, 2, 3, 3, 5)), // 9 -- sigma12 puis sigma23
        ) ;
    }
    
    /**
     * @depends test_init
     */
    public function testData(Geode $g) {
        
        $gn = new GeodeNode($g, 1, 2, 3, 4, 5) ;
        
        $this->assertNull($gn->getData()) ;
        
        $gn->setData(1) ;
        $this->assertEquals($gn->getData(), 1) ;
        
    }
    
    /**
     * @depends test_init
     * @dataProvider fromStringProvider
     */
    public function testFromString(Geode $g, $string, $vars) {
        
        $node1 = new GeodeNode($g, $vars[0], $vars[1], $vars[2], $vars[3], $vars[4]) ;
        $node2 = GeodeNode::fromString($g, $string) ;
        $this->assertTrue($node1->equals($node2)) ;
        
        $string2 = $node1->getString() ;
        $this->assertEquals($string, $string2) ;
    }
    
    public function fromStringProvider() {
        $g = new Geode(10) ;
        return array(
            array($g, "1 2 3 4 5", array(1, 2, 3, 4, 5)), // 0 -- nothing to do
            array($g, "1 2 - 4 0", array(1, 2, 0, 4, 0)), // 1 -- j = 0
            array($g, "1 - - 0 0", array(1, 0, 0, 0, 0)), // 2 -- i = 0 & j = 0
        ) ;
    }
    
    /**
     * @depends test_init
     */
    public function testNeighbour(Geode $g) {
        
        $gn = new GeodeNode($g, 1, 2, 3, 4, 5) ;
        
        $this->assertTrue(is_array($gn->getNeighbours())) ;
        $this->assertEquals(count($gn->getNeighbours()), 0) ;
        
        $gn2 = new GeodeNode($g, 1, 2, 3, 3, 5) ;
        $gn->setNeighbour(1, $gn2) ;
        $ns = $gn->getNeighbours() ;
        
        $this->assertTrue(is_array($ns)) ;
        $this->assertEquals(count($ns), 1) ;
        $this->assertTrue($gn2->equals($ns[1])) ;
    }
    
}
