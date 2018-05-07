<?php

namespace Quantyl\Misc\Geode ;

class NodeSet implements \Iterator {

    private $_geode ;
    private $_face ;
    private $_i ;
    private $_j ;
    private $_size ;
    
    private $_slerp_ab ;
    private $_slerp_ac ;
    private $_slerp_bc ;
    
    private $_current ;
    
    public function __construct(Geode $g) {
        $this->_geode = $g ;
        $this->_size = $g->getSize() ;
        
    }

    public function current() {
        return $this->_current ;
    }

    public function key() {
        return $this->_current->getString() ;
    }

    private function makeCurrent() {
        
        if ($this->_face >= count(Geode::$_faces)) {
            $this->_current = false ;
        } else {
            $this->_current = new GeodeNode(
                    $this->_geode,
                    Geode::$_faces[$this->_face][0],
                    Geode::$_faces[$this->_face][1],
                    Geode::$_faces[$this->_face][2],
                    $this->_i,
                    $this->_j) ;
            
            if ($this->_size - $this->_j == 0) {
                $t = 0 ;
            } else {
                $t = $this->_i / ($this->_size - $this->_j) ;
            }
            
            $pos = $this->_slerp_ab->interpolate($t) ;
            $this->_current->setData($pos) ;
        }
    }
    
    public function initFace() {
        if ($this->_face < count(Geode::$_faces)) {
            $face = Geode::$_faces[$this->_face] ;

            $vertexA = Geode::getMainPoint($face[0]) ;
            $vertexB = Geode::getMainPoint($face[1]) ;
            $vertexC = Geode::getMainPoint($face[2]) ;

            $this->_slerp_ac = new \Quantyl\Misc\Slerp($vertexA, $vertexC) ;
            $this->_slerp_bc = new \Quantyl\Misc\Slerp($vertexB, $vertexC) ;
        }
    }
    
    public function initRow() {
        
        $t = $this->_j / $this->_size ;
        $va = $this->_slerp_ac->interpolate($t) ;
        $vb = $this->_slerp_bc->interpolate($t) ;
        
        $this->_slerp_ab = new \Quantyl\Misc\Slerp($va, $vb) ;
    }
    
    public function next() {
        
        if ($this->_current === false) { return ; }
        
        $this->_i++ ;
        if ($this->_i + $this->_j > $this->_size) {
            $this->_i = 0 ;
            $this->_j++ ;
            
            if ($this->_j > $this->_size) {
                $this->_j = 0 ;
                $this->_face++ ;
                
                $this->initFace() ;
            }
            
            $this->initRow() ;
        }
        
        $this->makeCurrent() ;
        
    }

    public function rewind() {
        
        $this->_i = 0 ;
        $this->_j = 0 ;
        $this->_face = 0 ;
        $this->initFace() ;
        $this->initRow() ;
        
        $this->makeCurrent() ;
    }

    public function valid() {
        return $this->_current !== false ;
    }

}
