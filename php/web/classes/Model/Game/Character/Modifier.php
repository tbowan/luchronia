<?php

namespace Model\Game\Character ;

class Modifier extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "modifier" :
                return \Model\Game\Modifier::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "modifier" :
            case "character" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getActive(\Model\Game\Character $c, $since = null) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :cid and"
                . "     (`until` > :t or `until` = 0)",
                array(
                    "cid" => $c->id,
                    "t" => ($since === null ? time() : $since)
                )) ;
    }

}
