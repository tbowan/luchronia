<?php

namespace Quantyl\XML\SVG ;

class Text extends Shape {
    
    public function __construct($x, $y, $text)
    {
        parent::__construct("text") ;
        $this->setAttribute("x", $x);
        $this->setAttribute("y", $y);
        $this->addChild(new \Quantyl\XML\Raw($text)) ;
    }
    
}

?>
