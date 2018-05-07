<?php

namespace Model\Game\Ressource ;

class Modifier extends \Quantyl\Dao\BddObject {
    
    public function isGoodRace(\Model\Game\Character $character) {
        if ($this->race == null) {
            return true ;
        } else if ($this->race > 0) {
            return $character->race->getId() == $this->race ;
        } else {
            return $character->race->getId() == - $this->race ;
        }
    }
    
    public function isGoodSex(\Model\Game\Character $character) {
        if ($this->sex == null) {
            return true ;
        } else if ($this->sex > 0) {
            return $character->sex->getId() == $this->sex ;
        } else {
            return $character->sex->getId() == - $this->sex ;
        }
    }
    
    public function isUsable(\Model\Game\Character $character) {
        return
                $this->isGoodRace($character) &&
                $this->isGoodSex($character) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "modifier" :
                return \Model\Game\Modifier::GetById($value) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "modifier" :
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    
    // Get Some Sets
    
    public static function CanUse(\Model\Game\Character $c, Item $i) {
        foreach (self::GetByItem($i) as $e) {
            if ($e->isUsable($c)) {
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
    
}
