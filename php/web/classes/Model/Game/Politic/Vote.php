<?php

namespace Model\Game\Politic ;

class Vote extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "question" :
                return Question::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "question" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public static function hasVoted(Question $q, \Model\Game\Character $c) {
        $row = self::getSingleRow(""
                . " select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question`  = :qid and"
                . "     `character` = :cid",
                array(
                    "cid" => $c->id,
                    "qid" => $q->id
                )) ;
        return $row !== false ;
    }
    
    public static function getResult(Question $q) {
        $nbvoters = $q->system->countVoter() ;
        $nbvote = self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName(). "`"
                . " where `question` = :qid",
                array("qid" => $q->id)) ;
        $nbyes = self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName(). "`"
                . " where"
                . "     `question` = :qid and"
                . "     `value`    = 1",
                array("qid" => $q->id)) ;
        
        return array ( $nbvoters, $nbvote, $nbyes ) ;
    }
}
