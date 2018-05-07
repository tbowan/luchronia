<?php

namespace Quantyl\XML\Html ;

class Span extends \Quantyl\XML\Base {
    
    public function __construct($class = null, \Quantyl\XML\Element $content = null) {
        parent::__construct("span");
        if ($content != null) {
            $this->addChild($content) ;
        }
        if ($class != null) {
            $this->setAttribute("class", $class) ;
        }
    }
}
