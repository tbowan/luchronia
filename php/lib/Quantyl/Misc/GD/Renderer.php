<?php

namespace Quantyl\Misc\GD ;

class Renderer extends \Quantyl\Answer\Answer {

    private $image ;
    
    public function __construct(Image $i) {
        $this->_image = $i ;
    }

    public function getContent() {
        $this->_image->asPNG() ;
    }

    public function sendHeaders($srv) {
        $srv->header("Content-type: image/png");
    }

}
