<?php

namespace Model\Game\Building ;

class Library extends \Quantyl\Dao\BddObject {
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return Instance::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
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
