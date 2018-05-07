<?php

namespace Quantyl\Form\Fields ;

class Date extends DateTime {
    
    public function __construct($label, $mandatory = false) {
        parent::__construct($label, 0, $mandatory);
    }
    
    
    public function getInputHTML($name, $values) {
        $res  = $this->getSelect     ($name,    1,   31,    "day", $values["day"])    . " / " ;
        $res .= $this->getMonthSelect($name,                       $values["month"])  . " / " ;
        $res .= $this->getSelect     ($name, 1900, 2014,   "year", $values["year"]) ;
        return $res ;
    }

    public function parse($value) {

        $value["second"] = 0 ;
        $value["minute"] = 0 ;
        $value["hour"] = 0 ;
        
        return parent::parse($value);

    }
    
    public function unparse($value) {
        $values = parent::unparse($value);
        
        unset($values["second"]) ;
        unset($values["minute"]) ;
        unset($values["hour"]) ;
        
        return $values ;
    }
    
}

?>
