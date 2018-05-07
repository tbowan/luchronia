<?php

namespace Model\Game\Politic ;

class President extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "republic" :
                return Republic::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "question" :
                return ($value == null ? null : Question::GetById($value)) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "republic" :
            case "character" :
                return $value->getId() ;
            case "question" :
                return ($value == null ? null : $value->getId()) ;
            default :
                return $value ;
        }
    }

    public static function GetLastFromRepublic(Republic $r) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `republic` = :rid and"
                . "     isnull(`end`)",
                array("rid" => $r->id)) ;
    }
    
}
