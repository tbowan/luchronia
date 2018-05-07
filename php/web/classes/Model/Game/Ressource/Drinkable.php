<?php

namespace Model\Game\Ressource ;

class Drinkable extends \Quantyl\Dao\BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
                return $value->getId() ;
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
