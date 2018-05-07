<?php

use \Misc\Matrix ;
use \Misc\Vertex3D ;
use \Misc\Vertex4D ;

class Vertex3DTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        $v = Vertex3D::XYZ(1, 2, 3) ;
        
        $this->assertEquals($v->x(), 1) ;
        $this->assertEquals($v->y(), 2) ;
        $this->assertEquals($v->z(), 3) ;
        
        $m = Matrix::create(array(
            array(1),
            array(2),
            array(3),
        )) ;
        $this->assertInstanceOf("\\Misc\\Vertex3D", $m) ;
        $this->assertTrue($m->equals($v)) ;
    }
    
        
    public function test_toVertex4D() {
        
        $v4 = Vertex4D::XYZW(1, 2, 3, 1) ;
        $v3 = Vertex3D::XYZ (1, 2, 3) ;
        
        $res = $v3->toVertex4D() ;
        $this->assertTrue($v4->equals($res)) ;
    }
    
    /**
     * @dataProvider VectorProduct_Provider
     */
    public function test_VectorProduct(Vertex3D $a, Vertex3D $b, Vertex3D $c) {
        $p = Vertex3D::VectorProduct($a, $b) ;
        $this->assertTrue($p->equals($c)) ;
    }
    
    public function VectorProduct_Provider() {
        return array (
            array (Vertex3D::XYZ(1, 0, 0), Vertex3D::XYZ(0, 1, 0), Vertex3D::XYZ(0, 0, 1)),
            array (Vertex3D::XYZ(1, 0, 0), Vertex3D::XYZ(0, 0, 1), Vertex3D::XYZ(0,-1, 0)),
            array (Vertex3D::XYZ(0, 1, 0), Vertex3D::XYZ(1, 0, 0), Vertex3D::XYZ(0, 0,-1)),
            array (Vertex3D::XYZ(0, 1, 0), Vertex3D::XYZ(0, 0, 1), Vertex3D::XYZ(1, 0, 0)),
            array (Vertex3D::XYZ(0, 0, 1), Vertex3D::XYZ(1, 0, 0), Vertex3D::XYZ(0, 1, 0)),
            array (Vertex3D::XYZ(0, 0, 1), Vertex3D::XYZ(0, 1, 0), Vertex3D::XYZ(-1, 0, 0)),
        ) ;
    }
    
    /**
     * @dataProvider spheric_Provider
     */
    public function test_spheric(Vertex3D $v, $deg, $lat, $long) {
        $this->assertEquals($v->lattitude($deg), $lat) ;
        $this->assertEquals($v->longitude($deg), $long) ;
    }
    
    public function spheric_Provider() {
        return array(
            array(Vertex3D::XYZ( 1, 0, 0), true,   0, 90),
            array(Vertex3D::XYZ(-1, 0, 0), true,   0,270),
            array(Vertex3D::XYZ( 0, 1, 0), true,  90,  0),
            array(Vertex3D::XYZ( 0,-1, 0), true, -90,  0),
            array(Vertex3D::XYZ( 0, 0, 1), true,   0,  0),
            array(Vertex3D::XYZ( 0, 0,-1), true,   0,180),
        ) ;
    }
    
}
