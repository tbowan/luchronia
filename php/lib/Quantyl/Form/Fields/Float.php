<?php

namespace Quantyl\Form\Fields ;

class Float extends \Quantyl\Form\Field {
    
    public function getInputHTML($key, $value) {
        return "<input"
                . " type=\"text\""
                . " id=\"" . $key . "\""
                . " name=\"" . $key . "\""
                . " value=\"" . htmlspecialchars($value, ENT_QUOTES, "UTF-8") . "\""
                . " />\n" ;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($value) {
        
        if (is_numeric($value)) {
            return $value ;
        } else if ($this->isMandatory ()) {
            throw new \Exception() ;
        } else {
            return null ;
        }
    }

}
