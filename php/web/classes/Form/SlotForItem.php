<?php

namespace Form ;

use Model\Game\Ressource\Item;
use Model\Game\Ressource\Slot;
use Quantyl\Form\Model\Select;

class SlotForItem extends Select {
    
    private $_item ;
    
    public function __construct(Item $i, $label = null, $mandatory = false, $description = null) {
        $this->_item = $i ;
        parent::__construct(Slot::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Ressource\Equipable::GetByItem($this->_item) as $equip) {
            $slot = $equip->slot ;
            $choices[$slot->id] = $slot->getName() ;
        }
        return $choices ;
    }
    
}
