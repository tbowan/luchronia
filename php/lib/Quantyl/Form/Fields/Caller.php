<?php

namespace Quantyl\Form\Fields ;

class Caller extends Hidden {
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($value) {
        return $value;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function unparse($value) {
        return addslashes($value);
    }
    
}
