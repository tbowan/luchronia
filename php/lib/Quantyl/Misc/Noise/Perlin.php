<?php

namespace Quantyl\Misc\Noise ;

class Perlin implements Noise {
    
    private $_p ;
    private $_g ;
    private $_size ;
    
    public function __construct($size, $seed = null) {
        $this->_p    = new Permutation($seed) ;
        $this->_size = $size ;
        $this->_g = array (
            array ( 1,  1,  0),array (-1,  1,  0),array ( 1, -1,  0),array (-1, -1,  0),
            array ( 1,  0,  1),array (-1,  0,  1),array ( 1,  0, -1),array (-1,  0, -1),
            array ( 0,  1,  1),array ( 0, -1,  1),array ( 0,  1, -1),array ( 0, -1, -1)
        ) ;
    }
    
    private function _dot($g, $x, $y, $z) {
        return
                $g[0] * $x +
                $g[1] * $y +
                $g[2] * $z ;
    }
    
    private function _mix($a, $b, $t) {
        return $a + $t * ($b - $a) ;
    }
    
    private function _fade($t) {
        return $t * $t * $t * ( $t * ( $t * 6 - 15) + 10) ;
    }
    
    public function noise_1d($x) {
        return $this->noise_3d($x, 0, 0) ;
    }

    public function noise_2d($x, $y) {
        return $this->noise_3d($x, $y, 0) ;
    }

    private function _base($x, $y, $z, $i, $j, $k, $u, $v, $w) {
        $zi = $this->_p->value($z + $k) ;
        $yi = $this->_p->value($y + $j + $zi) ;
        $xi = $this->_p->value($x + $i + $yi) ;
        
        $grad = $this->_g[$xi % count($this->_g)] ;
        
        return $this->_dot($grad, $u - $i, $v - $j, $w - $k) ;
    }
    
    public function noise_3d($x, $y, $z) {
        $x = $x / $this->_size ;
        $y = $y / $this->_size ;
        $z = $z / $this->_size ;
        
        // Cube offset
        $xi = floor($x) ;
        $yi = floor($y) ;
        
        $zi = floor($z) ;
        
        // coordinate in cube
        $u = $x - $xi ;
        $v = $y - $yi ;
        $w = $z - $zi ;
        
        $n000 = $this->_base($xi, $yi, $zi, 0, 0, 0, $u, $v, $w) ;
        $n001 = $this->_base($xi, $yi, $zi, 0, 0, 1, $u, $v, $w) ;
        $n010 = $this->_base($xi, $yi, $zi, 0, 1, 0, $u, $v, $w) ;
        $n011 = $this->_base($xi, $yi, $zi, 0, 1, 1, $u, $v, $w) ;
        $n100 = $this->_base($xi, $yi, $zi, 1, 0, 0, $u, $v, $w) ;
        $n101 = $this->_base($xi, $yi, $zi, 1, 0, 1, $u, $v, $w) ;
        $n110 = $this->_base($xi, $yi, $zi, 1, 1, 0, $u, $v, $w) ;
        $n111 = $this->_base($xi, $yi, $zi, 1, 1, 1, $u, $v, $w) ;
        
        $t = $this->_fade($u) ;
        $nx00 = $this->_mix($n000, $n100, $t) ;
        $nx01 = $this->_mix($n001, $n101, $t) ;
        $nx10 = $this->_mix($n010, $n110, $t) ;
        $nx11 = $this->_mix($n011, $n111, $t) ;
        
        $t = $this->_fade($v) ;
        $nxy0 = $this->_mix($nx00, $nx10, $t) ;
        $nxy1 = $this->_mix($nx01, $nx11, $t) ;
        
        $t = $this->_fade($w) ;
        $nxyz = $this->_mix($nxy0, $nxy1, $t) ;
        
        return $nxyz ;
    }

}

