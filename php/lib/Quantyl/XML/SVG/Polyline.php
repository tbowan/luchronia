<?php

namespace Quantyl\XML\SVG ;

class Polyline extends Shape
{
    
    public function __construct()
    {
        parent::__construct("polyline") ;
    }
    
    public function setListOfPoints($listOfPoints)
    {
        $path = "" ;
        foreach ($listOfPoints as $p) {
            $path .= " {$p[0]},{$p[1]}" ;
        }
        $this->setAttribute("points", $path) ;
    }
    
}

?>
