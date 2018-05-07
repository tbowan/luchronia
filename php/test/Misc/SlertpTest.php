<?php

use \Misc\Vertex3D ;
use \Misc\Slerp ;

class SlertpTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @dataProvider interpolate_provider
     */
    public function test_interpolate(Slerp $s, $t, Vertex3D $v) {
        $res = $s->interpolate($t) ;
        $this->assertTrue($res->equals($v, 0.0001)) ;
    }
    
    public function interpolate_provider() {
        $a = Vertex3D::XYZ( 1, 0, 0) ;
        $b = Vertex3D::XYZ( 0, 1, 0) ;
        
        $s = new Misc\Slerp($a, $b) ;
        
        $r3 = sqrt(3.0) ;
        $r2 = sqrt(2.0) ;
        
        return array (
            array ($s, 0.0, $s->getP0()),
            array ($s, 1.0, $s->getP1()),
            array ($s, 1.0 / 3.0, Vertex3D::XYZ($r3 / 2.0, 0.5      , 0)), // PI/6
            array ($s, 0.5      , Vertex3D::XYZ($r2 / 2.0, $r2 / 2.0, 0)),
            array ($s, 2.0 / 3.0, Vertex3D::XYZ(0.5      , $r3 / 2.0, 0)),
        ) ;
    }
}

