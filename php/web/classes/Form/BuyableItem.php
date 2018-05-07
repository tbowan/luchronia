<?php

namespace Form ;

class BuyableItem extends \Quantyl\Form\Model\Select {
    
    private $_max ;
    
    public function __construct($max, $label = null, $mandatory = false, $description = null) {
        $this->_max = $max ;
        parent::__construct(\Model\Game\Ressource\Item::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $choices = array() ;
        if (! $this->isMandatory()) {
            $choices[0] = \I18n::NONE() ;
        }
        
        foreach (\Model\Game\Ressource\Item::GetBuyable($this->_max) as $i) {
            $choices[$i->id] = $i->getName() . " : " . $i->price ;
        }
        
        return $choices ;
        
    }
    
}
