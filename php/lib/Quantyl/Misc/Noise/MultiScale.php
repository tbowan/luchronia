<?php

namespace Quantyl\Misc\Noise ;

class MultiScale implements Noise {
    
    
    private $_noises ;
    
    public function __construct($size, $octaves, $type) {
        $this->_noises = array() ;
        for ($i = 0; $i< $octaves; $i++) {
            $this->_noises[$i] = new $type($size) ;
            $size /= 2 ;
        }
    }
    
    public function noise_1d($x) {
        return $this->noise_3d($x, 0, 0) ;
    }

    public function noise_2d($x, $y) {
        return $this->noise_3d($x, $y, 0) ;
    }

    public function noise_3d($x, $y, $z) {
        $d = 0.5 ;
        $s = 0 ;
        $res = 0.0 ;
        foreach ($this->_noises as $n) {
            $res += $d * $n->noise_3d($x, $y, $z) ;
            $s += $d ;
            $d /= 2.0 ;
        }
        return $res / $s;
    }

}
