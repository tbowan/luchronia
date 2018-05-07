<?php

namespace Quantyl\Answer ;

abstract class Answer {
    
    public function __construct() {
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        return ;
    }
    
    public abstract function getContent() ;
    
    public function __toString() {
        return $this->getContent();
    }
    
}
