<?php

namespace Form\Vote ;

class System extends \Quantyl\Form\Fields\Radio {
    
    public function __construct($label = null, $mandatory = false, $description = null) {
        parent::__construct($label, $mandatory, $description);
        $this->setChoices(array("0" => \I18n::NO(), "1" => \I18n::YES()));
    }
    
    public function parse($value) {
        $temp = parent::parse($value);
        return $temp == "1" ;
    }
    
    public function unparse($value) {
        return parent::unparse($value ? "1" : "0");
    }
    
    
}
