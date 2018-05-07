<?php

use \Misc\Matrix ;
use \Misc\Vertex ;

class VertexTest extends PHPUnit_Framework_TestCase {
    
    public function test_init() {
        $v = Matrix::create(array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(5)
        )) ;
        
        $this->assertInstanceOf("\\Misc\\Vertex", $v) ;
        $this->assertEquals($v->getValue(0), 1) ;
        $this->assertEquals($v->getValue(1), 2) ;
        $this->assertEquals($v->getValue(2), 3) ;
        $this->assertEquals($v->getValue(3), 4) ;
        $this->assertEquals($v->getValue(4), 5) ;
        
    }
    
    public function test_ScalarProduct() {
        $v = Matrix::create(array(
            array(1),
            array(2),
        )) ;
        
        $this->assertEquals($v->ScalarProduct($v), 5) ;
        
        $v2 = Matrix::create(array(
            array(2),
            array(1),
        )) ;
        $this->assertEquals($v->ScalarProduct($v2), 4) ;
        
    }
    
    public function test_length() {
        $v = Matrix::create(array(
            array(3),
            array(4),
        )) ;
        
        $this->assertEquals($v->length(), 5) ;
    }
    
    public function test_normalize() {
        $v = Matrix::create(array(
            array(3),
            array(4),
        )) ;
        $v->normalize() ;
        $this->assertEquals($v->getValue(0), 0.6, '', 0.00001) ;
        $this->assertEquals($v->getValue(1), 0.8, '', 0.00001) ;
    }
    
}

