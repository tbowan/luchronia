<?php

namespace Quantyl\Form\Fields ;

class TextArea extends Text {

    public function getInputHTML($key, $value)
    {
        $res  = "<textarea name=\"$key\">";
        $res .= htmlspecialchars($value, ENT_QUOTES, "UTF-8") ;
        $res .= "</textarea>";
        return $res;
    }
    
}
