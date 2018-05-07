<?php

namespace Widget\User ;

class ChangeAuthSuccess extends \Quantyl\Answer\Widget {
    
    private $_auth ;
    
    public function __construct($auth) {
        $this->_auth = $auth ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::CHANGE_SUCCESSFULL() . "</h2>" ;
        $res .= \I18n::YOU_ARE_AUTH_AS($this->_auth->getName()) ;
        
        return $res ;
        
    }

}
