<?php

namespace Quantyl\XML\Html ;

class Meter extends \Quantyl\XML\Base {

    public function __construct($min, $max, $value, $low = null, $high = null, $optimum = null) {
        
        parent::__construct("meter") ;
        $this->setAttribute("min",   $min) ;
        $this->setAttribute("max",   $max) ;
        $this->setAttribute("value", $value) ;
        if ($low     !== null) { $this->setAttribute("low", $low) ; }
        if ($high    !== null) { $this->setAttribute("high", $high) ; }
        if ($optimum !== null) { $this->setAttribute("optimum", $optimum) ; }
        
        $this->addChild(new \Quantyl\XML\Raw($value)) ;

    }
    
}
