<?php

namespace Widget\BackOffice ;

class CreationSuccess extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::CREATION_SUCESSFULL() . "</h2>" ;
        $res .= \I18n::CREATION_SUCCESS_MESSAGE() ;
        
        
        return $res ;
    }
    
}
