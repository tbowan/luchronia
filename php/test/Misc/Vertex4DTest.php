<?php


use \Misc\Matrix ;
use \Misc\Vertex3D ;
use \Misc\Vertex4D ;

class Vertex4DTest extends PHPUnit_Framework_TestCase {
    
    public function test_init() {
        $v = Vertex4D::XYZW(1, 2, 3, 4) ;
        
        $this->assertEquals($v->x(), 1) ;
        $this->assertEquals($v->y(), 2) ;
        $this->assertEquals($v->z(), 3) ;
        $this->assertEquals($v->w(), 4) ;
        
        $m = Matrix::create(array(
            array(1),
            array(2),
            array(3),
            array(4)
        )) ;
        $this->assertInstanceOf("\\Misc\\Vertex4D", $m) ;
        $this->assertTrue($m->equals($v)) ;
    }
    
    public function test_toVertex3D() {
        
        $v4 = Vertex4D::XYZW(4, 8, 12, 4) ;
        $v3 = Vertex3D::XYZ (1, 2, 3) ;
        
        $res = $v4->toVertex3D() ;
        $this->assertTrue($v3->equals($res)) ;
    }
    
}