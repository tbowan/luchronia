<?php

namespace Model\Game\Politic ;

class Choice extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "candidate" :
                return Candidate::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "candidate" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function hasVoted(Question $q, \Model\Game\Character $c) {
        $res = self::getSingleRow(""
                . " select true"
                . " from"
                . "     `" . self::getTableName() . "` as choice,"
                . "     `" . Candidate::getTableName() . "` as candidate"
                . " where"
                . "     candidate.question = :qid and"
                . "     candidate.id = choice.candidate and"
                . "     choice.character = :cid",
                array(
                    "qid" => $q->id,
                    "cid" => $c->id
                )) ;
        return $res !== false ;
    }
    
    public static function GetForQuestion(Question $q) {
        return self::getResult(""
                . " select choice.*"
                . " from"
                . "     `" . self::getTableName() . "` as choice,"
                . "     `" . Candidate::getTableName() . "` as candidate"
                . " where"
                . "     candidate.question = :qid and"
                . "     candidate.id = choice.candidate",
                array(
                    "qid" => $q->id
                )) ;
    }

}
