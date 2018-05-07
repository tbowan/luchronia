<?php

namespace Answer\Widget\User ;

class ChangeAuthSuccess extends \Quantyl\Answer\Widget {
    
    private $_auth ;
    
    public function __construct($auth) {
        parent::__construct() ;
        $this->_auth = $auth ;
    }
    
    public function getContent() {
        $res = "<h2>" . \I18n::CHANGE_SUCCESSFULL() . "</h2>" ;
        $res .= \I18n::CHANGE_AUTH_SUCCESS_MESSAGE() ;
        $res .= \I18n::YOU_ARE_AUTH_AS($this->_auth->getName()) ;
        
        return $res ;
        
    }

}
