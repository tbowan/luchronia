<?php

namespace Form ;

class FieldItem extends \Quantyl\Form\Model\Select {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $c, $label = null, $mandatory = false, $description = null) {
        $this->_city = $c ;
        parent::__construct(\Model\Game\Ressource\Item::getBddTable(), $label, $mandatory, $description) ;
    }
    
    public function initChoices() {
        
        $natural = \Model\Game\Ressource\Natural::GetFieldableFromCity($this->_city) ;
        
        $choices = array() ;
        foreach ($natural as $nat) {
            $choices[$nat->item->id] = $nat->item->getName() ;
        }
        return $choices ;
        
    }
    
}
