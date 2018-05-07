<?php

namespace Quantyl\Form\Fields ;

class Hidden extends Text {

    public function __construct($value = null, $mandatory = false, $description = null) {
        parent::__construct(null, $mandatory, $description);
        $this->setValue($value) ;
    }
    
    public function getInputHTML($key, $value) {
        return "<input"
                . " type=\"hidden\""
                . " name=\"$key\""
                . " value=\"" . htmlspecialchars($value, ENT_QUOTES, "UTF-8") . "\""
                . " />\n" ;
    }

}
