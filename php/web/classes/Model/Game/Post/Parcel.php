<?php

namespace Model\Game\Post ;

class Parcel extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "sender" :
            case "recipient" :
                return \Model\Game\Character::GetById($value) ;
            case "source" :
            case "destination" :
            case "origin" :
                return \Model\Game\City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "sender" :
            case "recipient" :
            case "source" :
            case "destination" :
            case "origin" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromRecipient(\Model\Game\Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `recipient` = :cid",
                array("cid" => $c->id)) ;
    }
    
    public static function CountFromRecipient(\Model\Game\Character $c) {
        $row = static::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where `recipient` = :cid",
                array("cid" => $c->id)) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
}
