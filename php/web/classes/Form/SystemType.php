<?php

namespace Form ;

use Model\Game\Politic\SystemType as MT;
use Quantyl\Form\Model\EnumSimple;

class SystemType extends EnumSimple {

    private $_townhalls ;
    
    public function __construct(\Model\Game\City $city, $label = null, $mandatory = true, $description = null) {
        $this->_townhalls = $city->getTownHalls() ;
        parent::__construct(MT::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoice() {
        
        $level = 0 ;
        foreach ($this->_townhalls as $inst) {
            $level = max($level, $inst->level) ;
        }
        
        $choices = array() ;
        foreach (MT::GetAll() as $st) {
            if ($st->isCompatible($level)) {
                $choices[$st->getId()] = $st->getName() ;
            }
        }
        
        return $choices ;
    }
    
}
