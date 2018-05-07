<?php

namespace Quantyl\XML\SVG ;

class SvgUse extends Shape 
{

    public function __construct($id, $x, $y)
    {
        parent::__construct("use") ;
        $this->setAttribute("x", $x) ;
        $this->setAttribute("y", $y) ;
        $this->setAttribute("xlink:href", "#" . $id) ;
    }

}

?>
