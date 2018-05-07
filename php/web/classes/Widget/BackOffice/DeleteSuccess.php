<?php

namespace Widget\BackOffice ;

class DeleteSuccess extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::DELETE_SUCESSFULL() . "</h2>" ;
        $res .= \I18n::DELETE_SUCCESS_MESSAGE() ;
        
        
        return $res ;
    }
    
}
