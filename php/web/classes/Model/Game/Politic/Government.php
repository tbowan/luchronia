<?php

namespace Model\Game\Politic ;

class Government extends \Quantyl\Dao\BddObject {
    
    public function isProject() {
        return $this->start == null && $this->question == null ;
    }
    
    public function canSee(\Model\Game\Character $c) {
        return ! $this->isProject() || $this->canManage($c) ;
    }
    
    public function canManage(\Model\Game\Character $c) {
        return $this->isProject() && $this->author->equals($c) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "system" :
                return System::GetById($value) ;
            case "author" :
                return \Model\Game\Character::GetById($value) ;
            case "question" :
                return ($value == "" ? null : Question::GetById($value) ) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "system" :
            case "author" :
                return $value->getId() ;
            case "question" :
                return ($value != null ? $value->getId() : null) ;
            default :
                return $value ;
        }
    }

    // Get some sets
    
    public static function getLog(\Model\Game\City $city) {
        return self::getResult(""
                . " select gov.*"
                . " from"
                . "     `" . self::getTableName() . "` as gov,"
                . "     `" . System::getTableName() . "` as sys"
                . " where"
                . "     gov.system = sys.id and"
                . "     not(isnull(gov.`start`))"
                . " order by"
                . "     gov.`start`",
                array("cid" => $city->id)) ;
    }
    
    public static function CurrentFromSystem(System $s) {
        $res = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `system` = :sid and"
                . "  `start` < :t and"
                . "  (isnull(`end`) or `end` > :t)",
                array(
                    "sid" => $s->id,
                    "t" => time()
                        )) ;
        return $res ;
    }
    
    public static function GetForSystem(System $s) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `system` = :sid and"
                . "  not isnull(`start`)"
                . " order by `start`",
                array("sid" => $s->id)) ;
    }
    
    public static function GetVotingForSystem(System $s) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `system` = :sid and"
                . "  isnull(`start`) and"
                . "  not isnull(`question`)",
                array("sid" => $s->id)) ;
    }
    
    public static function GetMine(\Model\Game\Character $a) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `author` = :aid",
                array("aid" => $a->id)) ;
    }
    
    public static function GetFromAuthor(\Model\Game\Character $author) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `author` = :cid",
                array("cid" => $c->id)) ;
    }
    
    public static function GetFromQuestion(Question $q) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `question` = :qid",
                array("qid" => $q->id)) ;
    }
}
