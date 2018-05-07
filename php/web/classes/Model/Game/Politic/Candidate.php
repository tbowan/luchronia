<?php

namespace Model\Game\Politic ;

class Candidate extends \Quantyl\Dao\BddObject {
    
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

    public static function GetFromQuestion(Question $q) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid"
                . " order by rank, rand()",
                array("qid" => $q->id)) ;
    }
    
    public static function GetCandidate(Question $q, \Model\Game\Character $c) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid and"
                . "     `character` = :cid",
                array(
                    "qid" => $q->id,
                    "cid" => $c->id
                        )) ;
    }
    
    public static function getChosen(Question $q) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid and"
                . "     `answer`"
                . " order by rand()",
                array("qid" => $q->id)) ;
    }
    
    public static function getChosenOne(Question $q) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid and"
                . "     `answer`"
                . " order by rand()",
                array("qid" => $q->id)) ;
    }
    
    /*
     * Tri des candidats en fonction du système électoral
     *  Ces requêtes rangent les candidats par rang
     */
    
    public static function sortByValues($rows) {
        $rank = 0 ;
        $value = 0 ;
        foreach ($rows as $row) {
            
            if ($row["votes"] != $value) {
                $value = $row["votes"] ;
                $rank += 1 ;
            }
            
            $candidate = \Model\Game\Politic\Candidate::GetById($row["candidate"]) ;
            $candidate->rank = $rank ;
            $candidate->update() ;
        }
    }
    
    public static function sortMajority(Question $q) {
        $rows = self::getStatement(""
                . " select"
                . "     candidate.id as candidate,"
                . "     count(choice.id) as votes"
                . " from"
                . "     `" . self::getTableName() . "` as candidate"
                . " left join"
                . "     `" . Choice::getTableName() . "` as choice"
                . " on"
                . "     choice.candidate = candidate.id"
                . " where"
                . "     candidate.`question` = :qid"
                . " group by candidate.id"
                . " order by votes",
                array("qid" => $q->id)) ;
        
        self::sortByValues($rows) ;
    }
    
    public static function sortAlternative(\Model\Game\Politic\Question $q) {
        $alternative = new VoteSystem\Alternative($q) ;
        $alternative->sort() ;
    }
    
    public static function sortBorda(\Model\Game\Politic\Question $q) {
        self::sortBorda($q) ;
    }
    
    public static function sortCumulative(\Model\Game\Politic\Question $q) {
        $rows = self::getStatement(""
                . " select"
                . "     candidate.id as candidate,"
                . "     sum(choice.value) as votes"
                . " from"
                . "     `" . self::getTableName() . "` as candidate"
                . " left join"
                . "     `" . Choice::getTableName() . "` as choice"
                . " on"
                . "     choice.candidate = candidate.id"
                . " where"
                . "     candidate.`question` = :qid"
                . " group by candidate.id"
                . " order by votes",
                array("qid" => $q->id)) ;
        
        self::sortByValues($rows) ;
    }
    
    public static function sortHare(\Model\Game\Politic\Question $q) {
        $hare = new VoteSystem\Hare($q) ;
        $hare->sort() ;
    }
    
    /*
     * Une fois les classements fait, détermier les élus potentiels
     */
    
    public static function CountRanked(Question $q, $max) {
        return self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `question` = :qid and"
                . "     `rank` > 0 and"
                . "     `rank` <= :max",
                array(
                    "qid" => $q->id,
                    "max" => $max
                        )) ;
    }
    
    public static function ChoseRanked(Question $q, $max) {
        return self::execRequest(""
                . " update `" . self::getTableName() . "`"
                . " set"
                . "     `answer` = (rank > 0 and rank <= :max)"
                . " where"
                . "     `question` = :qid",
                array(
                    "qid" => $q->id,
                    "max" => $max
                        )) ;
    }
    
}
