<?php

namespace Quantyl\XML\Html ;

class A extends \Quantyl\XML\Base {
    
    public function __construct($url, $title) {
        parent::__construct("a") ;
        $this->setAttribute("href", $url) ;
        $this->addChild(new \Quantyl\XML\Raw($title)) ;
    }
}

?>
