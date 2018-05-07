<?php

namespace Quantyl\XML\Html ;

class Div extends \Quantyl\XML\Base {
    
    public function __construct($class = null, \XML\Element $content = null) {
        parent::__construct("div");
        if ($content != null) {
            $this->addChild($content) ;
        }
        if ($class != null) {
            $this->setAttribute("class", $class) ;
        }
    }
}
