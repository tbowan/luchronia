<?php

namespace Quantyl\Answer ;

class Raw extends Answer {

    private $_type ;
    private $_content ;
    
    public function __construct($type, $content) {
        $this->_type    = $type ;
        $this->_content = $content ;
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        $srv->header("Content-type: " . $this->_type) ;
    }

    public function getContent() {
        return $this->_content ;
    }
}
