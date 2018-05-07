<?php

namespace Answer\Decorator ;

class Ajax extends \Quantyl\Answer\Answer {
    
    private $_content ;
    
    public function __construct(\Quantyl\Service\EnhancedService $srv, $msg) {
        $this->_content = $msg ;
    }
    
    public function getContent() {
        return $this->_content ;
    }


    public function sendHeaders(\Quantyl\Server\Server $srv) {
        $srv->header("Content-type: application/json");
    }
}
