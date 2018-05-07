<?php

namespace Quantyl\Misc\Noise ;

class Permutation {

    private $_p ;
    
    public function __construct($seed = null) {
        if ($seed !== null) {
            srand($seed) ;
        }
        
        $this->_p = array() ;
        for ($i = 0; $i<256; $i++) {
            $this->_p[$i] = $i ;
        }
        
        for ($i = 0; $i<256; $i++) {
            $j = rand($i, 255) ;
            $t = $this->_p[$i] ;
            $this->_p[$i] = $this->_p[$j] ;
            $this->_p[$j] = $t ;
        }
    }
    
    public function value($v) {
        $i = $v % 256 ;
        if ($i < 0) {
            $i+= 256 ;
        }
        return $this->_p[$i] ;
    }
    
}
