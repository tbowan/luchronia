<?php

namespace Model\Game\Politic ;

class Parliament extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "parliamentary" :
                return Parliamentary::GetById($value) ;
            case "question" :
                return ($value == null ? null : Question::GetById($value)) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "parliamentary" :
                return $value->getId() ;
            case "question" :
                return ($value == null ? null : $value->getId()) ;
            default :
                return $value ;
        }
    }
    
    public static function getLastFromParliamentary(Parliamentary $p) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `parliamentary` = :pid and"
                . "     not isnull(start) and"
                . "     isnull(end)",
                array(
                    "pid" => $p->id
                )) ;
    }
    
    public static function getFromQuestion(Question $q) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid",
                array(
                    "qid" => $q->id
                )) ;
    }

}
