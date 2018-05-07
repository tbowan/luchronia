<?php

namespace Quantyl\Form\Fields ;

class Radio extends \Quantyl\Form\Choice {
    
    public function getInputHTML($key, $value) {
        $res = "\n" ;
        foreach ($this->getChoices() as $k => $label) {
            if ($k == $value) {
                $checked = " checked=\"\"" ;
            } else {
                $checked = "" ;
            }
            $res .= "\t<div class=\"choice\">\n" ;
            $res .= "\t\t<input"
                    . " type=\"radio\""
                    . " id=\"" . $key . "-" . $k . "\""
                    . " name=\"" . $key . "\""
                    . " value=\"" . $k . "\""
                    . $checked
                    . " />\n" ;
            $res .= "\t\t<label"
                    . " for=\"" . $key . "-" . $k . "\""
                    . ">"
                    . $label
                    . "</label>\n" ;
            $res .= "\t</div>\n" ;
        }
        
        return $res ;
    }
    
}
