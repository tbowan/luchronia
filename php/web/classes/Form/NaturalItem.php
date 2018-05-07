<?php

namespace Form ;

use Model\Game\City;
use Model\Game\Ressource\Item;
use Model\Game\Ressource\Natural;
use Quantyl\Form\Model\Select;

class NaturalItem extends Select {
    
    private $_city ;
    
    public function __construct(City $c, $label = null, $mandatory = false, $description = null) {
        $this->_city = $c ;
        parent::__construct(Item::getBddTable(), $label, $mandatory, $description) ;
    }
    
    public function initChoices() {
        
        $natural = Natural::GetFromCity($this->_city) ;
        
        $choices = array() ;
        foreach ($natural as $nat) {
            $choices[$nat->item->id] = $nat->item->getName() ;
        }
        return $choices ;
        
    }
    
}
