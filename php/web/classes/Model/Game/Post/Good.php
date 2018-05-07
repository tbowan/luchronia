<?php

namespace Model\Game\Post ;

class Good extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "parcel" :
                return Parcel::GetById($value) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "parcel" :
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromParcel(Parcel $p) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `parcel` = :pid",
                array("pid" => $p->id)) ;
    }
    
}
