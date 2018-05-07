<?php

namespace Form\Tool ;

class Move extends Base {
    
    private $_cost ;
    
    public function __construct(\Model\Game\Skill\Character $cs, \Model\Game\Tax\Complete $tax, $cost) {
        $this->_cost = $cost ;
        parent::__construct($cs, $tax);
    }
    
    public function getTime($tool) {
        return round($this->_cost * parent::getTime($tool) / $this->_cs->level) ;
    }
    
    public function getTitleGain() {
        return \I18n::OPERATIONS() ;
    }
    
    
}
