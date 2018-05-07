<?php

namespace Model\Game\Building ;

class Construction extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "type" :
                return Type::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "type" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromType(Type $t) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where"
                . " `type` = :tid",
                array (
                    "tid"   => $t->id
                )) ;
    }
    
}
