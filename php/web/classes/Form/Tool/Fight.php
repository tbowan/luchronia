<?php

namespace Form\Tool ;

class Fight extends Base {
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_cs->level * (1 + 4 * $this->_cs->skill->cost);
    }
    
    public function getTitleGain() {
        return \I18n::DAMAGES() ;
    }

}
