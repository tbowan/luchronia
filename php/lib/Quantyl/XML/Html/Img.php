<?php

namespace Quantyl\XML\Html ;

class Img extends \Quantyl\XML\Base {
    
    public function __construct($url, $alt, $class = null) {
        parent::__construct("img") ;
        $this->setAttribute("src", $url) ;
        $this->setAttribute("alt", $alt) ;
        if ($class != null) {
            $this->setAttribute("class", $class) ;
        }
    }
}

?>
