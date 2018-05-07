<?php

namespace Model\Game\Ressource ;

class Eatable extends \Quantyl\Dao\BddObject {

    
    public function canEat(\Model\Game\Character $c) {
        if ($this->race == null) {
            return true ;
        } else {
            return $this->race->equals($c->race) ;
        }
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "race" :
                return ($value == null ? null : \Model\Enums\Race::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
                return $value->getId() ;
            case "race" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public static function GetByItem(Item $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array("iid" => $i->id)
                ) ;
    }
    
}
