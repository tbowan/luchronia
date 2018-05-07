<?php

namespace Quantyl\Form\Fields ;

class Email extends Text {
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($value) {
        $temp = parent::parse($value) ;
        
        if ($temp != "" && ! filter_var($temp, FILTER_VALIDATE_EMAIL)) {
            // TODO better exception
            throw new \Exception() ;
        }
        
        return $temp ;
    }
    
    public function unparse($value) {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $value = "" ;
        }
        return parent::unparse($value);
    }
    
}
