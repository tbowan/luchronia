<?php

namespace Quantyl\Form\Fields ;

class Password extends \Quantyl\Form\Field {
    
    public function getInputHTML($key, $value) {
        return "<input"
                . " type=\"password\""
                . " name=\"" . $key . "\""
                . " value=\"\""
                . " />\n" ;
    }

}
