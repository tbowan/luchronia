<?php

namespace Form ;

class Inventory extends \Quantyl\Form\Model\Select {
    
    private $_char ;
    
    public function __construct(\Model\Game\Character $char, $label = null, $mandatory = false, $description = null) {
        $this->_char = $char ;
        parent::__construct(\Model\Game\Ressource\Inventory::getBddTable(), $label, $mandatory, $description);
    }
    
    
    public function initChoices() {
        
        $choices = array() ;
        foreach (\Model\Game\Ressource\Inventory::GetFromCharacter($this->_char) as $inv) {
            $choices[$inv->id] = $inv->item->getName() . " " . $inv->amount ;
        }
        return $choices ;
    }
}
