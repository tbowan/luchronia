<?php

namespace Widget\Game\Inventory ;

use Model\Game\Character;
use Model\Game\Ressource\Equipable;
use Model\Game\Ressource\Inventory;
use Model\Game\Ressource\Slot;
use Quantyl\Answer\Widget;
use Quantyl\XML\Html\A;
use Quantyl\XML\Html\Table;

class Slots extends Widget {
    
    private $_character ;
    
    public function __construct(Character $c) {
        $this->_character = $c ;
    }
    
    private function _buildTable() {
        $table = new Table() ;
        $table->addHeaders(array(
            \I18n::SLOT(),
            \I18n::ITEM(),
            \I18n::QTTY(),
            \I18n::ACTIONS()
        )) ;
        return $table ;
    }
    
    private function _actions($inventory) {
        $item = $inventory->item ;
        $res = "" ;
        $res .= new A("/Game/Inventory/Drop?id={$inventory->id}", \I18n::INVENTORY_DROP()) ;
        $res .= new A("/Game/Inventory/Group?id={$inventory->id}", \I18n::INVENTORY_GROUP()) ;
        $res .= new A("/Game/Inventory/UnEquip?id={$inventory->id}", \I18n::INVENTORY_UNEQUIP()) ;
        return $res ;
    }
    
    private function _itemTable(Slot $s, Inventory $i, Table &$table) {
        $table->addRow(array(
            $s->getName(),
            $i->item->getImage("icone-med") . $i->item->getName(),
            $i->amount,
            $this->_actions($i)
        )) ;
        return Equipable::GetNeededPlaces($i->item, $s) ;
    }
    
    private function _freeTable(Slot $s, $number, Table &$table) {
        for ($i = 0; $i < $number; $i++) {
            $table->addRow(array(
                $s->getName(),
                \I18n::INVENTORY_FREE_SLOT(),
                "-",
                "-"
            )) ;
        }
    }
    
    private function _aSlot(Slot $s, Table &$table) {
        $cnt = 0 ;
        foreach (Inventory::GetEquiped($this->_character, $s) as $inventory) {
            $cnt += $this->_itemTable($s, $inventory, $table) ;
        }
        $this->_freeTable($s, $s->amount - $cnt, $table) ;
    }
    
    
    public function getContent() {
        $res = "<h2>" . \I18n::EQUIPMENT() . "</h2>" ;
        
        $table = $this->_buildTable() ;
        
        foreach (Slot::GetAll() as $slot) {
            $this->_aSlot($slot, $table) ;
        }
        $res .= $table ;
        
        return $res ;
    }
    
}
