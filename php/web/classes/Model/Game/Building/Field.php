<?php

namespace Model\Game\Building ;

use Model\Game\Ressource\Item;
use Quantyl\Dao\BddObject;

class Field extends BddObject {
    
    public function getMaxAmount() {
        return $this->instance->health * 10 ;
    }
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return Instance::GetById($value) ;
            case "item" :
                return Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(Instance $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->id)) ;
    }
    
}
