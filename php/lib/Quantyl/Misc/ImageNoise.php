<?php

namespace Quantyl\Misc ;

class ImageNoise {
    
    private $_image ;
    private $_dx ;
    private $_dy ;
    private $_width ;
    private $_height ;
    private $_d ;
    
    public function __construct($filename, $d = null) {
        $this->_image  = \Quantyl\Misc\GD\Image::FromFile($filename) ;
        $this->_width  = $this->_image->getWidth()  - 1;
        $this->_height = $this->_image->getHeight() - 1;
        $this->_dx     = $this->_width  / 360.0 ;
        $this->_dy     = $this->_height / 180.0 ;
        $this->_d      = ($d === null ? 0 : $d) ;
    }

    public function noise($long, $latt) {
        
        $x = $long ;
        $y = $latt ;
        
        $xm = intval($this->_dx * (180 + $x)) ;
        $ym = intval($this->_dy * (90  - $y)) ;
        
        // echo "$x, $y => $xm, $ym \t{$this->_width}, {$this->_height}\n" ;
        $sum = 0 ;
        $nb  = 0 ;
        
        for ($a = max(0, $xm-$this->_d); $a < min($this->_width, $xm+$this->_d+1); $a++) {
            for ($b = max(0, $ym-$this->_d); $b < min($this->_height, $ym+$this->_d+1); $b++) {
                $c = $this->_image->getColor($xm, $ym) ;
                list ($red, $green, $blue) = $c->getRGBA() ;
                $sum += $red + $green + $blue ;
                $nb  += 3 ;
            }
        }
        
        return $sum / $nb ;
    }
    
}
