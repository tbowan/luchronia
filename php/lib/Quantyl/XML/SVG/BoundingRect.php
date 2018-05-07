<?php

namespace Quantyl\XML\SVG ;

class BoundingRect {
    
    private $_min_x ;
    private $_max_x ;
    private $_min_y ;
    private $_max_y ;
    
    public function __construct() {
        $this->_min_x = null ;
        $this->_max_x = null ;
        $this->_min_y = null ;
        $this->_max_y = null ;
    }
    
    private function updateMinX($x) {
        if ($this->_min_x == null || $this->_min_x > $x) {
            $this->_min_x = $x ;
        }
    }
    
    private function updateMaxX($x) {
        if ($this->_max_x == null || $this->_max_x < $x) {
            $this->_max_x = $x ;
        }
    } 
    
    private function updateMinY($y) {
        if ($this->_min_y == null || $this->_min_y > $y) {
            $this->_min_y = $y ;
        }
    }
    
    private function updateMaxY($y) {
        if ($this->_max_y == null || $this->_max_y < $y) {
            $this->_max_y = $y ;
        }
    }
    
    public function updateCoord($x, $y) {
        $this->updateMinX($x) ;
        $this->updateMaxX($x) ;
        $this->updateMinY($y) ;
        $this->updateMaxY($y) ;
    }
    
    public function updateRect($rect) {
        $this->updateMinX($rect->xl()) ;
        $this->updateMaxX($rect->xb()) ;
        $this->updateMinY($rect->yl()) ;
        $this->updateMaxY($rect->yb()) ;
    }
    
    public function xl() {
        return $this->_min_x ;
    }
    
    public function yl() {
        return $this->_min_y ;
    }
    
    public function xb() {
        return $this->_max_x ;
    }
    
    public function yb() {
        return $this->_max_y ;
    }
    
    public function width() {
        return $this->_max_x - $this-> _min_x ;
    }
    
    public function height() {
        return $this->_max_y - $this->_min_y ;
    }
    
}

?>
