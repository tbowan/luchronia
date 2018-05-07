<?php

namespace Quantyl\Answer ;

class Message extends Widget {
    
    private $_msg ;
    
    public function __construct($msg) {
        parent::__construct() ;
        $this->_msg = $msg ;
    }
    
    public function getContent() {
        return $this->_msg ;
    }
}
