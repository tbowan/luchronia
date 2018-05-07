<?php

namespace Quantyl\Misc\Geode ;

class GeodeNode {
    
    private $_geode ;
    
    // Sommets du triangle qui le contient
    private $_a, $_b, $_c ;
    // coordonnées dans le triangle
    private $_i, $_j ;
    
    private $_neighbours ;
    
    private $_data ;
    
    public function __construct($geode, $a, $b = null, $c = null, $i = 0, $j = 0) {
        
        $this->_geode = $geode ;
        $this->_a = $a ;
        $this->_b = $b ;
        $this->_c = $c ;
        $this->_i = $i ;
        $this->_j = $j ;
        
        $this->_neighbours = array() ;
        $this->_data = null ;
        
        $this->normalize() ;
    }
    
    public function getA() {
        return $this->_a ;
    }
    
    public function getB() {
        return $this->_b ;
    }
    
    public function getC() {
        return $this->_c ;
    }
    
    public function getI() {
        return $this->_i ;
    }
    
    public function getJ() {
        return $this->_j ;
    }
    
    public function setData($data) {
        $this->_data = $data ;
    }
    
    public function getData() {
        return $this->_data ;
    }
    
    public function setNeighbour($offset, $gn) {
        $this->_neighbours[$offset] = $gn ;
    }
    
    public function getNeighbours() {
        return $this->_neighbours ;
    }
    
    private function sigma12() {
        $t        = $this->_a;
        $this->_a = $this->_b;
        $this->_b = $t;
        $this->_i = $this->_geode->getSize() - $this->_i - $this->_j;
    }
    
    private function sigma13() {
        $t        = $this->_a;
        $this->_a = $this->_c;
        $this->_c = $t;
        $this->_j = $this->_geode->getSize() - $this->_j - $this->_i;
    }
    
    private function sigma23() {
        $t        = $this->_b;
        $this->_b = $this->_c;
        $this->_c = $t;

        $t        = $this->_i;
        $this->_i = $this->_j;
        $this->_j = $t;
    }
    
    private function normalize() {
        $size = $this->_geode->getSize() ;
        
        if ($this->_i == 0 && $this->_b != 0) {
            $this->_b = null ;
            $this->normalize() ;
        } else if ($this->_j == 0 && $this->_c != 0) {
            $this->_c = null ;
            $this->normalize() ;
        } else if ($this->_j == $size) {
            $this->_a = $this->_c ;
            $this->_b = null ;
            $this->_c = null ;
            $this->_i = 0 ;
            $this->_j = 0 ;
            $this->normalize() ;
        } else if ($this->_i == $size) {
            $this->_a = $this->_b ;
            $this->_b = null ;
            $this->_c = null ;
            $this->_i = 0 ;
            $this->_j = 0 ;
            $this->normalize() ;
        } else if ($this->_j !== 0 && $this->_i + $this->_j == $this->_geode->getSize()) {
            $this->_a = $this->_c;
            $this->_c = null;
            $this->_j = 0;
            $this->normalize() ;
        } else if ($this->_b !== null && ($this->_b < $this->_a ||$this->_a === null)) {
            $this->sigma12() ;
            $this->normalize() ;
        } else if ($this->_c !== null && ($this->_c < $this->_a || $this->_a === null)) {
            $this->sigma13() ;
            $this->normalize() ;
        } else if ($this->_c !== null && ($this->_c < $this->_b || $this->_b === null)) {
            $this->sigma23() ;
            $this->normalize() ;
        } else {
            return ;
        }
    }
    
    public function equals($node) {
        return 
                $this->getA() == $node->getA() &&
                $this->getB() == $node->getB() &&
                $this->getC() == $node->getC() &&
                $this->getI() == $node->getI() &&
                $this->getJ() == $node->getJ() ;
    }
    
    public function __toString() {
        return $this->getString() ;
    }
    
    public function getString() {
        return     "" . self::toStr($this->_a)
                . " " . self::toStr($this->_b)
                . " " . self::toStr($this->_c)
                . " " . $this->_i
                . " " . $this->_j ;
    }
    
    private static function toStr($a) {
        if ($a === null) {
            return "-" ;
        } else {
            return $a ;
        }
    }
    
    private static function fromStr($s) {
        if ($s == "-") {
            return null ;
        } else {
            return intval($s) ;
        }
    }
    
    // Static constructs
    
    public static function fromString(Geode $g, $abcij) {
        $vars = explode(" ", $abcij) ;
        return new GeodeNode(
                $g,
                self::fromStr($vars[0]),
                self::fromStr($vars[1]),
                self::fromStr($vars[2]),
                intval($vars[3]),
                intval($vars[4])
                ) ;
    }
    
}
