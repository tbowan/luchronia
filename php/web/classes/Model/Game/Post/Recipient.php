<?php

namespace Model\Game\Post ;

class Recipient extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "mail" :
                return Mail::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "mail" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `recipient` = :cid",
                array("cid" => $c->id)) ;
    }
    
    public static function GetFromMail(\Model\Game\Post\Mail $m) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `mail` = :mid",
                array("mid" => $m->id)) ;
    }    
}
