<?php

namespace Answer\Widget\Game\Ressource ;

class Inventory extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Character $me, $classes = "") {
        
        parent::__construct(
                \I18n::INVENTORY(),
                "",
                "",
                $this->makeInventoryList($me),
                $classes);
    }
    
    private function getLinks(\Model\Game\Character $me, \Model\Game\Ressource\Inventory $inv) {
        
        $actions = "" ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Drop?id={$inv->id}", \I18n::INVENTORY_DROP_ICON()) ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Group?id={$inv->id}", \I18n::INVENTORY_GROUP_ICON()) ;
        
        if ($inv->isEquipable()) {
            $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Equip?id={$inv->id}", \I18n::INVENTORY_EQUIP_ICON()) ;
        }
        
        if ($inv->isUsable()) {
            $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/UseItem?id={$inv->id}", \I18n::INVENTORY_USE_ICON()) ;
        }
        $actions .= \I18n::HELP("/Help/Item?id={$inv->item->id}") ;
        
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
        $res .= "<div class=\"icon\">" . \I18n::INVENTORY_FREE_SLOT_ICON() . "</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . \I18n::INVENTORY_FREE_SLOT() . "</div>"
                . "<div class=\"qtty\">" . new \Quantyl\XML\Html\Meter(0, 100, 0) . "0 / 100 </div>"
                . "<div class=\"links\">-</div>"
                . "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    private function makeNew(\Model\Game\Character $me) {
        
        $cost = $me->inventory - 4 ;
        $avail = $me->point ;
        
        $res  = "<li class=\"card-1-2\"><div class=\"item\">" ;
        $res .= "<div class=\"icon\">" . \I18n::INVENTORY_NEW_SLOT_ICON() . "</div>" ;
        $res .= "<div class=\"description\">"
                . "<div class=\"name\">" . \I18n::INVENTORY_NEW_SLOT() . "</div>"
                . "<div class=\"cost\">" . \I18n::COST() . " : $cost / $avail </div>"
                . "<div class=\"links\">" . new \Quantyl\XML\Html\A("/Game/LevelUp/Slot", \I18n::BUY()) . "</div>"
                . "</div>" ;
        $res .= "</div></li>" ;
        return $res ;
    }
    
    private function makeInventoryList(\Model\Game\Character $me) {
        $res = "" ;
        $res .= "<ul class=\"itemList\">" ;
        
        $cnt = 0 ;
        foreach (\Model\Game\Ressource\Inventory::GetFromCharacter($me) as $inv) {
            $res .= $this->makeItem($me, $inv) ;
            $cnt++ ;
        }
        
        while ($cnt < $me->inventory) {
            $res .= $this->makeEmpty() ;
            $cnt++ ;
        }
        
        $res .= $this->makeNew($me) ;
        
        $res .= "</ul>" ;
        return $res ;
        
        
    }
    
    
}
