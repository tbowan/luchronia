<?php

namespace Form\Tool ;

class Prospection extends Base {
    
    public function getTime($tool) {
        return round(parent::getTime($tool) / $this->_cs->level) ;
    }
    
    public function getTitleGain() {
        return \I18n::OPERATIONS() ;
    }
    
    
}
