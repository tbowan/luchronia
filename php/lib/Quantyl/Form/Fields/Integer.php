<?php

namespace Quantyl\Form\Fields ;

class Integer extends \Quantyl\Form\Field {
    
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
        $iv = intval($value) ;
        if ($value === null || "$iv" == $value) {
            return $iv ;
        } else {
            throw new \Exception() ;
        }
    }

}
