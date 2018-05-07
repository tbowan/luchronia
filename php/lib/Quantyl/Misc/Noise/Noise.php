<?php

namespace Quantyl\Misc\Noise ;

interface Noise {
    
    public function noise_1d($x) ;
    
    public function noise_2d($x, $y) ;
    
    public function noise_3d($x, $y, $z) ;
    
}
