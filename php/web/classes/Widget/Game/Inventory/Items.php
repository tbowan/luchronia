<?php

namespace Widget\Game\Inventory ;

class Items extends \Quantyl\Answer\Widget {
    
    private $_character ;
    
    public function __construct(\Model\Game\Character $c) {
        $this->_character = $c ;
    }
    
    private function _buildTable() {
        $table = new \Quantyl\XML\Html\Table() ;
        $table->addHeaders(array(
            \I18n::ICONE(),
            \I18n::ITEM(),
            \I18n::QTTY(),
            \I18n::USEIT(),
            \I18n::ACTIONS()
        )) ;
        return $table ;
    }
    
    private function _actions($inventory) {
        $item = $inventory->item ;
        $actions  = new \Quantyl\XML\Html\A("/Game/Inventory/Drop?id={$inventory->id}", \I18n::INVENTORY_DROP()) ;
        $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Group?id={$inventory->id}", \I18n::INVENTORY_GROUP()) ;
        
        if (\Model\Game\Ressource\Equipable::CanEquip($this->_character, $item)) {
            $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Equip?id={$inventory->id}", \I18n::INVENTORY_EQUIP()) ;
        }
        if (\Model\Game\Ressource\Modifier::CanUse($this->_character, $item)) {
            $actions .= new \Quantyl\XML\Html\A("/Game/Inventory/Modifier?id={$inventory->id}", \I18n::INVENTORY_MODIFIER()) ;
        }
        return $actions ;
    }
    
    private function _special($inventory) {
        $item = $inventory->item ;
        $special = "" ;
        
        if ($inventory->isEatable() || $inventory->isDrinkable()) {
            $special .= new \Quantyl\XML\Html\A("/Game/Inventory/Feed", \I18n::ITEM_FEED()) ;
        }
        
        if (\Model\Game\Ressource\Parchment::GetByItem($item) != null) {
            $special .= new \Quantyl\XML\Html\A("/Game/Inventory/Learn/Parchment?inventory={$inventory->id}", \I18n::ITEM_LEARN()) ;
        }
        if (\Model\Game\Ressource\Book::GetByItem($item) != null) {
            $special .= new \Quantyl\XML\Html\A("/Game/Inventory/Learn/Book?inventory={$inventory->id}", \I18n::ITEM_LEARN()) ;
        }
        return ($special == "" ? "-" : $special ) ;
    }
    
    private function _itemTable(&$table) {
        $available = $this->_character->inventory ;
        $used = 0 ;
        foreach (\Model\Game\Ressource\Inventory::GetFromCharacter($this->_character) as $inventory) {
            $i = $inventory->item ;
            $table->addRow(array(
                $i->getImage("icone-med"),
                 $i->getName() . " " . \I18n::HELP("/Help/Item?id={$i->id}"),
                $inventory->amount,
                $this->_special($inventory),
                $this->_actions($inventory)
            )) ;
            $used++ ;
        }
        return $available - $used ;
    }
    
    private function _freeTable($number, &$table) {
        for ($i = 0; $i < $number; $i++) {
            $table->addRow(array(
                "-",
                \I18n::INVENTORY_FREE_SLOT(),
                "-",
                "-",
                "-"
            )) ;
        }
    }
    
    public function getContent() {
        
        $res = "<h2>" . \I18n::INVENTORY() . "</h2>" ;
        $table = $this->_buildTable() ;
        $free = $this->_itemTable($table) ;
        $this->_freeTable($free, $table) ;
        $res .= $table ;
        
        
        $res .= \I18n::INVENTORY_NEW_SLOT($this->_character->inventory, $this->_character->inventory - 4, $this->_character->point) ;
        
        return $res ;
    }
    
}
