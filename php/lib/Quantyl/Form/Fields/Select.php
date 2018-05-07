<?php

namespace Quantyl\Form\Fields ;

class Select extends \Quantyl\Form\Choice {
    
    public function getInputHTML($key, $value) {
        $res = "\n" ;
        $res  .= "<select name=\"$key\" >\n" ;
        $choices = $this->getChoices() ;
        asort($choices, SORT_STRING) ;
        foreach ($choices as $k => $label) {
            if ($k == $value) {
                $checked = " selected=\"\"" ;
            } else {
                $checked = "" ;
            }
            $res .= "\t<option"
                    . " value=\"" . $k . "\""
                    . $checked
                    . " >\n"
                    . "\t$label\n"
                    . "\t</option>\n" ;
        }
        $res .= "</select>\n" ;
        return $res ;
    }
    
}
