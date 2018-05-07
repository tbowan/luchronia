<?php

namespace Form\Tool ;

class Trading extends Base {
    
    public function __construct(\Model\Game\Skill\Character $cs) {
        parent::__construct($cs, new \Model\Game\Tax\Complete(0.0, 0.0));
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_cs->level ;
    }
    
    public function getTitleGain() {
        return \I18n::QUANTITY() ;
    }
}
