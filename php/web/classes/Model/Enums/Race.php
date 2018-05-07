<?php

namespace Model\Enums ;

class Race extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        1 => "HUMAN",
        2 => "CYBORG" ,
        3 => "SELENITE",
    ) ;
    
    
    public function getEatStr() {
        switch($this->getId()) {
            case 1 :
                return \I18n::ITEM_EAT_HUMAN() ;
            case 2 :
                return \I18n::ITEM_EAT_CYBORG() ;
            case 3 :
                return \I18n::ITEM_EAT_SELENITE() ;
        }
    }
    
    public function initInventory(\Model\Game\Character $c) {
        \Model\Game\Ressource\Inventory::AddItem($c, \Model\Game\Ressource\Item::GetByName("Water"), 100) ;
        
        switch ($this->getId()) {
            case 1 :
                \Model\Game\Ressource\Inventory::AddItem($c, \Model\Game\Ressource\Item::GetByName("AvoroBread"), 100) ;
                break ;
            case 2 :
                \Model\Game\Ressource\Inventory::AddItem($c, \Model\Game\Ressource\Item::GetByName("Coke"), 18) ;
                break ;
            case 3 :
                \Model\Game\Ressource\Inventory::AddItem($c, \Model\Game\Ressource\Item::GetByName("Sand"), 100) ;
                break ;
                
        }
    }
        
    
}
