<?php

namespace Quantyl\Answer ;

class ListAnswer extends Widget {
    
    private $_answers ;
    
    public function __construct() {
        $this->_answers = array() ;
    }
    
    public function addAnswer(Answer $a) {
        if ($a !== null) {
            $this->_answers[] = $a ;
        }
    }
    
    public function sendHeaders(\Quantyl\Server\Server $srv) {
        foreach ($this->_answers as $a) {
            $a->sendHeaders($srv) ;
        }
    }
    
    public function getContent() {
        $content = "" ;
        foreach ($this->_answers as $a) {
            $content .= $a->getContent() ;
        }
        return $content ;
    }
}
