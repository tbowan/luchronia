<?php

namespace Quantyl\Answer ;

class Redirect extends Answer {
    
    private $_target ;
    private $_temporary ;
    
    public function __construct($target, $temporary = true) {
        $this->_target    = $target ;
        $this->_temporary = $temporary ;
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        if ($this->_temporary) {
            $srv->header("Location: " . $this->_target, true, 302) ;
        } else {
            $srv->header("Location: " . $this->_target, true, 301) ;
        }
    }
    
    public function getContent() {
        return null;
    }
}
