<?php

namespace Model\Game\Ressource ;

use Model\Game\City;
use Quantyl\Dao\BddObject;

class Delivery extends BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "target" :
                return City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "target" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromTarget(City $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `target` = :cid",
                array ("cid" => $c->id)
                ) ;
    }
    
    public static function GetFromBackOffice() {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `backoffice`"
                . " order by scheduled",
                array ()
                ) ;
    }
    
}
