<?php

namespace Widget\Game\Character ;

class LinkedName extends \Quantyl\Answer\Widget {
    
    private $_character ;
    
    public function __construct(\Model\Game\Character $char) {
        $this->_character = $char ;
    }
    
    public function getContent() {
        $char = $this->_character ;
        
        $link = new \Quantyl\XML\Html\A(
                "/Game/Character/Show?id={$char->id}",
                $char->getName()
                        ) ;
        
        return "$link" ;
    }
    
}
