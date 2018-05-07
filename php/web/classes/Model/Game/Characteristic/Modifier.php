<?php

namespace Model\Game\Characteristic ;

class Modifier extends \Quantyl\Dao\BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "modifier" :
                return \Model\Game\Modifier::GetById($value) ;
            case "characteristic" :
                return \Model\Game\Characteristic::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "modifier" :
            case "characteristic" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getBonusFor(\Model\Game\Character $c, \Model\Game\Characteristic $carac) {
        
        return self::getResult(""
                . " select ci.*"
                . " from"
                . "     `game_character_modifier`       as ca,"
                . "     `game_characteristic_modifier`  as ci"
                . " where"
                . "     ci.modifier         = ca.modifier       and"
                . "     ca.`character`      = :caid             and"
                . "     (ca.`until` > :t or ca.`until` = 0)  and"
                . "     ci.`characteristic` = :ciid",
                array(
                    "ciid" => $carac->id,
                    "caid" => $c->id,
                    "t" => time()
                )) ;
        
    }
    
    public static function GetByModifier(\Model\Game\Modifier $m) {
        return self::getResult(
                "select * from `" . self::getTableName() . "` where `modifier` = :mid",
                array("mid" => $m->id)
                ) ;
    }
}
