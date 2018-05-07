<?php

namespace Model\Game\Politic ;

class System extends \Quantyl\Dao\BddObject implements PoliticalSystem {
    
    public function countVoter() {
        return $this->getPoliticalSystem()->countVoter() ;
    }
    
    public function canManage(\Model\Game\Character $c) {
        $now = time() ;
        if ($this->start == null) {
            return false ;
        } else if ($this->end != null && $this->end < $now) {
            return false ;
        } else {
            return $this->getPoliticalSystem()->canManage($c) ;
        }
    }

    public function doChange(System $target) {
        $this->getPoliticalSystem()->doChange($target) ;
        $target->doStart() ;
    }
    
    public function doStart() {
        $this->getPoliticalSystem()->doStart() ;
    }

    public function tryChange(System $target) {
        return $this->getPoliticalSystem()->tryChange($target) ;
    }
    
    public function createGovernmentProject(\Model\Game\Character $author) {
        return $this->getPoliticalSystem()->createGovernmentProject($author) ;
    }

    public function proceedGovernmentProject(Government $gov) {
        return $this->getPoliticalSystem()->proceedGovernmentProject($gov) ;
    }
    
    public function getPoliticalSystem() {
        return $this->type->getPoliticalSystem($this) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "question" :
                return ($value == null ? null : Question::GetById($value)) ;
            case "type" :
                return SystemType::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "type" :
                return $value->getId() ;
            case "question" :
                return ($value == null ? null : $value->getId()) ;
            default :
                return $value ;
        }
    }

    
    // Get some sets
    
    public static function LastFromCity(\Model\Game\City $c, $time = null) {
        if ($time == null) {
            $time = time() ;
        }
        $res = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :cid and"
                . "  `start` < :t and"
                . "  (isnull(`end`) or `end` > :t)"
                . " order by `start` desc"
                . " limit 1",
                array(
                    "cid" => $c->id,
                    "t" => $time
                        )) ;
        if ($res == null) {
            return self::Anarchy($c) ;
        } else {
            return $res ;
        }
    }
    
    public static function GetLastEnd(\Model\Game\City $c, $time = null) {
        if ($time == null) {
            $time = time() ;
        }
        $res = static::getSingleRow(
                "select `end` from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :cid and"
                . "  not isnull(`end`) and"
                . "  `end` < :t"
                . " order by `end` desc"
                . " limit 1",
                array(
                    "cid" => $c->id,
                    "t" => $time
                        )) ;
        if ($res == null) {
            return 0 ;
        } else {
            return $res["end"] ;
        }
    }
    
    public static function ImuneUntil(\Model\Game\City $c) {
        $last = self::GetLastEnd($c) ;
        return $last + IMUNE_DELAY ;
    }
    
    public static function LostCity(\Model\Game\City $c, $time = null) {
        if ($time == null) {
            $time = time() ;
        }
        $res = static::execRequest(
                "update `" . self::getTableName() . "`"
                . " set `end` = :t"
                . " where"
                . "  `city` = :cid and"
                . "  `start` < :t and"
                . "  (isnull(`end`) or `end` > :t)",
                array(
                    "cid" => $c->id,
                    "t" => $time
                        )) ;
    }
    
    public static function Anarchy(\Model\Game\City $c) {
        $res        = new System() ;
        $res->id    = 0 ;
        $res->city  = $c ;
        $res->type  = SystemType::Anarchy() ;
        $res->name  = "" ;
        $res->start = null ;
        $res->end   = null ;
        
        return $res ;
    }

    public static function GetFromSystem(System $base) {
        return $base ;
    }
    
    public static function GetFromQuestion(Question $q) {
        return self::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `question` = :qid",
                array("qid" => $q->id)) ;
    }

}
