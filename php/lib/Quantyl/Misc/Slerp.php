<?php

namespace Quantyl\Misc ;

class Slerp {

    private $_p0 ;
    private $_p1 ;
    private $_omega ;
    
    /**
     * Construct a slerp and copies the points
     * @param \Misc\Vertex3D $p0 first point (copied)
     * @param \Misc\Vertex3D $p1 last point (copied)
     */
    public function __construct(Vertex3D $p0, Vertex3D $p1) {
        
        $dot = $p0->ScalarProduct($p1) ;
        $acos = $dot / ($p0->length() * $p1->length()) ;
        $this->_omega = acos(min(max($acos, 0), 1));
        
        $this->_p0 = clone $p0 ;
        
        if ($this->_omega == 0) {
            $this->_p1 = clone $this->_p0 ;
        } else {
            $b = clone $p0 ;
            $b->multiply(-1.0 * cos($this->_omega)) ;
            $b->add($p1) ;
            $b->multiply(1.0 / sin($this->_omega)) ;
            $this->_p1 = $b ;
        }
    }
    
    public function getOmega() {
        return $this->_omega ; 
    }
    
    public function getP0() {
        return $this->_p0 ;
    }
    
    public function getP1() {
        return $this->_p1 ;
    }
    
    /**
     * Interpolate along the arc
     * @param type $t
     */
    public function interpolate($t) {
        $alpha = $t * $this->_omega ;
        
        $p0 = clone $this->_p0 ;
        $p0->multiply(cos($alpha)) ;

        $p1 = clone $this->_p1 ;
        $p1->multiply(sin($alpha)) ;

        return Vertex3D::sum($p0, $p1) ;
    }
    
    
}
