<?php

namespace Form\Tool ;

class Heal extends Base {
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_cs->level ;
    }
    
    public function displayAmount($amount) {
        return 100 * parent::displayAmount($amount);
    }
    
    public function getTitleGain() {
        return \I18n::HEAL_POINT() ;
    }

}
