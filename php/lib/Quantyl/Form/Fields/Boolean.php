<?php

namespace Quantyl\Form\Fields ;

class Boolean extends \Quantyl\Form\Field {
    
    public function getInputHTML($key, $value) {
        if ($value == "1") {
            return "<input type=\"checkbox\" name=\"$key\" value=\"1\" checked=\"\" />" ;
        } else {
            return "<input type=\"checkbox\" name=\"$key\" value=\"1\" />" ;
        }
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($value) {
        return (intval($value) === 1) ;
    }
    
    public function unparse($value) {
        if ($value) {
            return "1" ;
        } else {
            return null ;
        }
    }
    
}
