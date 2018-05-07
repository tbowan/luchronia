<?php

namespace Model\Game\Politic ;

class Parliamentarian extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "parliament" :
                return Parliament::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "parliament" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function canManage(Parliament $p, \Model\Game\Character $c) {
        return self::getTrue(""
                . " select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `parliament` = :pid and"
                . "     `character` = :cid",
                array(
                    "pid" => $p->id,
                    "cid" => $c->id
                )) ;
    }
    
    public static function GetFromParliament(Parliament $p) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `parliament` = :pid",
                array(
                    "pid" => $p->id
                )) ;
    }

    public static function CountFromParliament(Parliament $p) {
        return self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `parliament` = :pid",
                array(
                    "pid" => $p->id
                )) ;
    }
}
