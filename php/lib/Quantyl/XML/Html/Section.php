<?php

namespace Quantyl\XML\Html ;

class Section extends \Quantyl\XML\Base {
    
    public function __construct($title, $level = 1) {
        parent::__construct("div");
        $this->setAttribute("class", "section") ;
        $this->addChild(new Head($level, $title)) ;
    }
    
}

?>