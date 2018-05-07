<?php

use \Misc\Matrix ;
use \Misc\Matrix4D ;
use \Misc\Vertex3D ;

class Matrix4DTest extends PHPUnit_Framework_TestCase {

    public function test_init() {
        
        $m = Matrix::create(array(
            array(1, 0, 0, 0),
            array(0, 1, 0, 0),
            array(0, 0, 1, 0),
            array(0, 0, 0, 1),
        )) ;
        
        $i = Matrix::I(4) ;
        
        $this->assertTrue($i->equals($m)) ;
        $this->assertInstanceOf("\\Misc\\Matrix4D", $m) ;
        $this->assertInstanceOf("\\Misc\\Matrix4D", $i) ;
        
    }

    public function test_rotateX() {
        $a = Vertex3D::XYZ(0, 1, 0) ;
        $b = Vertex3D::XYZ(0, 0, 1) ;
        $c = Vertex3D::XYZ(0,-1, 0) ;
        
        $r1 = Matrix4D::rotationX(pi() / 2.0) ;
        $r2 = Matrix4D::rotationX(pi()) ;
        
        $a1 = $r1->transform3D($a) ;
        $a2 = $r2->transform3D($a) ;
        $b1 = $r1->transform3D($b) ;
        
        $this->assertTrue($a1->equals($b, 0.0001)) ;
        $this->assertTrue($a2->equals($c, 0.0001)) ;
        $this->assertTrue($b1->equals($c, 0.0001)) ;
    }
    
    public function test_rotateY() {
        $a = Vertex3D::XYZ(0, 0, 1) ;
        $b = Vertex3D::XYZ(1, 0, 0) ;
        $c = Vertex3D::XYZ(0, 0,-1) ;
        
        $r1 = Matrix4D::rotationY(pi() / 2.0) ;
        $r2 = Matrix4D::rotationY(pi()) ;
        
        $a1 = $r1->transform3D($a) ;
        $a2 = $r2->transform3D($a) ;
        $b1 = $r1->transform3D($b) ;
        
        $this->assertTrue($a1->equals($b, 0.0001)) ;
        $this->assertTrue($a2->equals($c, 0.0001)) ;
        $this->assertTrue($b1->equals($c, 0.0001)) ;
    }
    
    public function test_rotateZ() {
        $a = Vertex3D::XYZ(1, 0, 0) ;
        $b = Vertex3D::XYZ(0, 1, 0) ;
        $c = Vertex3D::XYZ(-1, 0,0) ;
        
        $r1 = Matrix4D::rotationZ(pi() / 2.0) ;
        $r2 = Matrix4D::rotationZ(pi()) ;
        
        $a1 = $r1->transform3D($a) ;
        $a2 = $r2->transform3D($a) ;
        $b1 = $r1->transform3D($b) ;
        
        $this->assertTrue($a1->equals($b, 0.0001)) ;
        $this->assertTrue($a2->equals($c, 0.0001)) ;
        $this->assertTrue($b1->equals($c, 0.0001)) ;
    }
    
    public function test_rotation() {
        $x = Vertex3D::XYZ(1, 0, 0) ;
        $y = Vertex3D::XYZ(0, 1, 0) ;
        $z = Vertex3D::XYZ(0, 0, 1) ;
        
        $axe = Vertex3D::XYZ(1, 1, 1) ;
        $r = Matrix4D::Rotation3D(2.0 * pi() / 3.0, $axe) ;
        
        $xt = $r->transform3D($x) ;
        $yt = $r->transform3D($y) ;
        $zt = $r->transform3D($z) ;
        
        echo "XT - " . $xt->x() . " " . $xt->y() . " " . $xt->z() . "\n" ; 
        echo "YT - " . $yt->x() . " " . $yt->y() . " " . $yt->z() . "\n" ; 
        echo "ZT - " . $zt->x() . " " . $zt->y() . " " . $zt->z() . "\n" ; 
        
        $this->assertTrue($x->equals($zt, 0.0001)) ;
        $this->assertTrue($y->equals($xt, 0.0001)) ;
        $this->assertTrue($z->equals($yt, 0.0001)) ;
    }
    
    public function test_translation() {
        $a = Vertex3D::XYZ(1, 2, 3) ;
        $b = Vertex3D::XYZ(4, 5, 6) ;
        
        
        $t1 = Matrix4D::translation3D($b) ;
        $t2 = Matrix4D::translationXYZ(7, 8, 9) ;
        
        $a1 = $t1->transform3D($a) ;
        $a2 = $t2->transform3D($a) ;
        
        $r1 = Vertex3D::XYZ(5, 7, 9) ;
        $r2 = Vertex3D::XYZ(8, 10, 12) ;
        
        $this->assertTrue($a1->equals($r1, 0.0001)) ;
        $this->assertTrue($a2->equals($r2, 0.0001)) ;
    }
}
