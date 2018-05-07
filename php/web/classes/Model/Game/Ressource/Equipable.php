<?php

namespace Model\Game\Ressource ;

class Equipable extends \Quantyl\Dao\BddObject {

    public function isGoodRace(\Model\Game\Character $character) {
        if ($this->race == 0) {
            return true ;
        } else if ($this->race > 0) {
            return $character->race->getId() == $this->race ;
        } else {
            return $character->race->getId() == - $this->race ;
        }
    }
    
    public function isGoodSex(\Model\Game\Character $character) {
        if ($this->sex == 0) {
            return true ;
        } else if ($this->sex > 0) {
            return $character->sex->getId() == $this->sex ;
        } else {
            return $character->sex->getId() == - $this->sex ;
        }
    }
    
    public function isEquipable(\Model\Game\Character $character) {
        return
                $this->isGoodRace($character) &&
                $this->isGoodSex($character) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "slot" :
                return \Model\Game\Ressource\Slot::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "slot" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
 
    public static function CanEquip(\Model\Game\Character $c, Item $i) {
        foreach (self::GetByItem($i) as $e) {
            if ($e->isEquipable($c)) {
                return true ;
            }
        }
        return false ;
    }
    
    public static function GetByItem(Item $i) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array("iid" => $i->id)
                ) ;
    }
    
    public static function GetByItemAndSlot(Item $i, Slot $s) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid and `slot` = :sid",
                array("iid" => $i->id, "sid" => $s->id)
                ) ;
    }
    
    public static function GetNeededPlaces(Item $i, Slot $s) {
        $row = static::getSingleRow(
                "select amount from `" . self::getTableName() . "`"
                . " where `item` = :iid and `slot` = :sid",
                array("iid" => $i->id, "sid" => $s->id)
                ) ;
        return $row["amount"] ;
    }
    
}
