<?php

namespace Quantyl\Form\Fields ;

class Text extends \Quantyl\Form\Field {
    
    
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
        return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function unparse($value) {
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }

}
