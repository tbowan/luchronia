<?php

namespace Form\Tool ;

class Learn extends Base {
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_cs->level ;
    }
    
    public function getTitleGain() {
        return \I18n::LEARNED_POINTS() ;
    }
    
    
}
