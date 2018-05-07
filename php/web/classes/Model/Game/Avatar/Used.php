<?php

namespace Model\Game\Avatar ;

class Used extends \Quantyl\Dao\BddObject{
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return Item::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "character" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid"
                , array("cid" => $c->id)) ;
    }
    
}