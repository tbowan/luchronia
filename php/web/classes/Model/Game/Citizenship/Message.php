<?php

namespace Model\Game\Citizenship ;

class Message extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "citizenship" :
                return \Model\Game\Citizenship::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "citizenship" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCitizenship(\Model\Game\Citizenship $c) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where `citizenship` = :id",
                array("id" => $c->id)
                ) ;
    }
    
}
