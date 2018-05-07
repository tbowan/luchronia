<?php

namespace Form ;

class InventoryFromItem extends \Quantyl\Form\Model\Select {
    
    private $_char ;
    private $_item ;
    
    public function __construct(\Model\Game\Character $char, \Model\Game\Ressource\Item $i, $label = null, $mandatory = false, $description = null) {
        $this->_char = $char ;
        $this->_item = $i ;
        parent::__construct(\Model\Game\Ressource\Inventory::getBddTable(), $label, $mandatory, $description);
    }
    
    
    public function initChoices() {
        
        $choices = array() ;
        foreach (\Model\Game\Ressource\Inventory::GetFromItem($this->_char, $this->_item) as $inv) {
            $choices[$inv->id] = $inv->item->getName() . " " . $inv->amount ;
        }
        return $choices ;
    }
}
