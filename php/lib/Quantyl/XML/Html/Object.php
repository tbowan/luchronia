<?php

namespace Quantyl\XML\Html ;

class Object extends \Quantyl\XML\Base {
    
    public function __construct($data, $type) {
        parent::__construct("object") ;
        $this->setAttribute("data", $data) ;
        $this->setAttribute("type", $type) ;
        $this->addChild(new \Quantyl\XML\Raw("")) ;
    }
}

?>
