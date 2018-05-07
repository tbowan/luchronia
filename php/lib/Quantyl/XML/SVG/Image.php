<?php

namespace Quantyl\XML\SVG ;

class Image extends Shape {
    
    private $_bouding ;
    
    public function __construct($url, $x, $y, $width, $height) {
        parent::__construct("image") ;
        $this->setAttribute("xlink:href", $url) ;
        $this->setAttribute("x", $x) ;
        $this->setAttribute("y", $y) ;
        $this->setAttribute("width", $width) ;
        $this->setAttribute("height", $height) ;
        
        $this->_bouding = new \Quantyl\XML\SVG\BoundingRect(
                $x,
                $y,
                $x + $width,
                $y + $height
                ) ;
    }

    public function getBoudingRect() {
        return $this->_bouding ;
    }
}

?>
