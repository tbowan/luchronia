<?php

namespace Quantyl\Form\Fields ;

class Percentage extends Integer {
    
    public function parse($value) {
        $v = parent::parse($value) ;
        if ($v < 0) {
            throw new \Exception(\I18n::EXP_FORM_BELOW_MIN(0)) ;
        } else if ($v > 100) {
            throw new \Exception(\I18n::EXP_FORM_HIGHER_MAX(100)) ;
        } else {
            return $v / 100.0 ;
        }
    }
    
    public function unparse($value) {
        return parent::unparse($value * 100.0);
    }
    
}
