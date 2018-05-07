<?php

namespace Answer\Widget\Game\Ressource ;

class Equipment extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        parent::__construct(
                \I18n::EQUIPMENT(),
                "",
                "",
                $this->makeInventoryList($me),
                $classes);
    }
    
    private function getLinks(\Model\Game\Character $me, \Model\Game\Ressource\Inventory $inv) {
        
        $actions = "" ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Drop?id={$inv->id}", \I18n::INVENTORY_DROP_ICON()) ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Group?id={$inv->id}", \I18n::INVENTORY_GROUP_ICON()) ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/UnEquip?id={$inv->id}", \I18n::INVENTORY_EQUIP_ICON()) ;
        
        if ($inv->isUsable()) {
            $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/UseItem?id={$inv->id}", \I18n::INVENTORY_USE_ICON()) ;
        }
        
        return $actions ;
        
    }
    
    private function makeItem(\Model\Game\Character $me, \Model\Game\Ressource\Inventory $inv) {
        $res  = "<li class=\"card-1-2\"><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . $inv->item->getImage() . "</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . $inv->item->getName() . "</div>"
                . "<div class=\"qtty\">" . new \Quantyl\XML\Html\Meter(0, 100, $inv->amount) . $inv->amount . " / 100 </div>"
                . "<div class=\"links\">" . $this->getLinks($me, $inv) . "</div>"
                . "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    private function makeEmpty() {
        $res  = "<li class=\"card-1-2\"><div class=\"item\">" ;
        $res .= "<div class=\"icon\"></div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . \I18n::INVENTORY_FREE_SLOT() . "</div>"
                . "<div class=\"qtty\">" . new \Quantyl\XML\Html\Meter(0, 100, 0) . "0 / 100 </div>"
                . "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    
    private function makeSlot(\Model\Game\Character $me, \Model\Game\Ressource\Slot $slot) {
        $res  = "<h2>" . $slot->getName() . " x " .  $slot->amount . "</h2>" ;
        $res .= "<ul class=\"itemList\">" ;
        $cnt = 0 ;
        foreach (\Model\Game\Ressource\Inventory::GetEquiped($me, $slot) as $inv) {
            $msg = $this->makeItem($me, $inv) ;
            $use  = \Model\Game\Ressource\Equipable::GetNeededPlaces($inv->item, $slot) ;
            for ($i=0; $i<$use; $i++) {
                $res .= $msg ;
            }
            $cnt += $use ;
        }
        
        while ($cnt < $slot->amount) {
            $res .= $this->makeEmpty() ;
            $cnt++ ;
        }
        $res .= "</ul>" ;
        
        return $res ;
    }
    
    private function makeInventoryList(\Model\Game\Character $me) {
        $res = "" ;
        
        foreach (\Model\Game\Ressource\Slot::GetAll() as $slot) {
            $res .= $this->makeSlot($me, $slot) ;
        }
        
        return $res ;
        
        
    }
    
    
}
