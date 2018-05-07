<?php

namespace Quantyl\Misc\Noise ;

class Simplex implements Noise {
    
    private $_g ;
    private $_p ;
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
    
    private function _dot($g, $v) {
        return
                $g[0] * $v[0] +
                $g[1] * $v[1] +
                $g[2] * $v[2] ;
    }
    
    public function noise_1d($x) {
        return $this->noise_3d($x, 0, 0) ;
    }

    public function noise_2d($x, $y) {
        return $this->noise_3d($x, $y, 0) ;
    }

    private function getPath($v) {
        
        $x = $v[0] ; $y = $v[1] ; $z = $v[2] ;
        
        $points = array (
            0 => array (0, 0, 0),
            3 => array (1, 1, 1)
        ) ;
        
        if ($x > $y) {
            if ($y > $z) {                      // X > Y > Z
                $points[1] = array (1, 0, 0) ;
                $points[2] = array (1, 1, 0) ;
            } else if ($x > $z) {               // X > Z > Y
                $points[1] = array(1, 0, 0) ;
                $points[2] = array(1, 0, 1) ;
            } else {                            // Z > X > Y
                $points[1] = array (0, 0, 1) ;
                $points[2] = array (1, 0, 1) ;
            }
        } else {
            if ($y < $z) {                     // Z > Y > X
                $points[1] = array (0, 0, 1) ;
                $points[2] = array (0, 1, 1) ;
            } else if ($x < $z) {              // Y > Z > X
                $points[1] = array (0, 1, 0) ;
                $points[2] = array (0, 1, 1) ;
            } else {                           // Y > X > Z
                $points[1] = array (0, 1, 0) ;
                $points[2] = array (1, 1, 0) ;
            }
        }
        
        return $points ;
    }
    
    private function _skew($v) {
        $K = 1.0 / 3.0 ;
        $s = ($v[0] + $v[1] + $v[2]) * $K ;
        return array ($v[0] + $s, $v[1] + $s, $v[2] + $s) ;
    }

    private function _getBase($v) {
        return array (floor($v[0]), floor($v[1]), floor($v[2])) ;
    }
    
    private function _unskew($v) {
        $C = 1.0 / 6.0 ;
        $s = ($v[0] + $v[1] + $v[2]) * $C ;
        return array ($v[0] - $s, $v[1] - $s, $v[2] - $s) ;
    }
    
    private function _diff($a, $b) {
        return array ($a[0] - $b[0], $a[1] - $b[1], $a[2] - $b[2]) ;
    }
    
     private function _add($a, $b) {
        return array ($a[0] + $b[0], $a[1] + $b[1], $a[2] + $b[2]) ;
    }
    
    public function getGrad($point) {
        $res = 0 ;
        foreach ($point as $c) {
            $res = $this->_p->value($res + $c) ;
        }
        return $this->_g[$res % count($this->_g)] ;
    }
    
    public function noise_3d($x, $y, $z) {
        // Point coordinate
        $point       = array($x / $this->_size, $y / $this->_size, $z / $this->_size) ;
        // Point coordinate in simplex system
        $skewed      = $this->_skew($point) ;
        // Coordinate of origin cell in simplex system
        $base        = $this->_getBase($skewed) ;
        // Coordinate of origin cell in base system
        $base_unskew = $this->_unskew($base) ;
        // Coordinate of point in simplex (in base system)
        $AP          = $this->_diff($point, $base_unskew) ;

        // List of points to look up
        $points = $this->getPath($AP) ;
        
        $noise = 0.0 ;
        
        foreach ($points as $p) {
            $vertex_skew   = $this->_add($base, $p) ;
            $vertex_unskew = $this->_unskew($vertex_skew) ;
            $AP = $this->_diff($point, $vertex_unskew) ;
            $contrib = 0.5 - $this->_dot($AP, $AP) ;
            if ($contrib > 0) {
                $contrib *= $contrib ;
                $grad = $this->getGrad($vertex_skew) ;
                $noise += $contrib * $contrib * $this->_dot($AP, $grad) ;
            }
            
        }
        
        // Pour être entre -1 et 1
        // "Magic constant"
        return 75.0 * $noise ;
    }

}

