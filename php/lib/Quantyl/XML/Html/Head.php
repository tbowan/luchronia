<?php

namespace Quantyl\XML\Html ;

class Head extends \Quantyl\XML\Base {
    
    public function __construct($level, $rawcontent) {
        parent::__construct("h$level");
        $this->addChild(new \Quantyl\XML\Raw($rawcontent)) ;
    }
    
}
