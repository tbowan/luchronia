<?php

namespace Widget\BackOffice ;

class EditSuccess extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::EDIT_SUCESSFULL() . "</h2>" ;
        $res .= \I18n::EDIT_SUCCESS_MESSAGE() ;
        
        
        return $res ;
    }
    
}
