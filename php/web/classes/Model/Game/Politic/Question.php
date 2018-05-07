<?php

namespace Model\Game\Politic ;

class Question extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "type" :
                return QuestionType::GetById($value) ;
            case "vote" :
                return VoteType::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "type" :
            case "vote" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function GetFromSystem(System $s) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `system` = :sid"
                . " order by"
                . "     `end` desc",
                array("sid" => $s->id)) ;
    }
    
    public static function toProceed() {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `end` < :time and"
                . "     not `processed`",
                array("time" => time())) ;
    }

}
