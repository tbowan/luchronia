<?php

namespace Quantyl\XML\SVG ;

class A extends \Quantyl\XML\Base
{
    public function __construct($url) {
        parent::__construct("a") ;
        $this->setAttribute("xlink:href", $url) ;
    }
}

?>
