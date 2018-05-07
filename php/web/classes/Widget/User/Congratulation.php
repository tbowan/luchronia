<?php

namespace Widget\User ;

class Congratulation extends \Quantyl\Answer\Widget {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $c) {
        $this->_char = $c ;
    }
    
    public function getContent() {
        $res = \I18n::CONGRATULATION_TOP() ;
        
        $file = "/Media/animations/BD/" . $this->_char->race->getValue() . "-" . $this->_char->sex->getValue() . ".svg" ;
        $res .= "<object type=\"image/svg+xml\" data=\"$file\"/>" ;
        
        return $res ;
    }
    
}
