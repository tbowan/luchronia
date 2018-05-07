<?php

namespace Model\Game\Trading ;

class Skill extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "instance" :
                return \Model\Game\Building\Instance::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "instance" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getSummary(\Model\Game\Building\Instance $instance, \Model\Game\Skill\Skill $skill) {
        return self::getSingleRow(""
                . " select"
                . "     min(price)  as `best`,"
                . "     max(price)  as `worst`,"
                . "     sum(remain) as `total`"
                . " from"
                . "     `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid and"
                . "     `skill`    = :sid and"
                . "     `remain`   > 0",
                array(
                    "iid" => $instance->id,
                    "sid" => $skill->id
                )) ;
    }
    
    public static function getForSkill(\Model\Game\Building\Instance $i, \Model\Game\Skill\Skill $s) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid and"
                . "     `skill`    = :sid and"
                . "     `remain`   > 0",
                array(
                    "iid" => $i->id,
                    "sid" => $s->id
                )) ;
    }
    
    public static function getForCharacter(\Model\Game\Building\Instance $i, \Model\Game\Character $c) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid and"
                . "     `character`    = :cid",
                array(
                    "iid" => $i->id,
                    "cid" => $c->id
                )) ;
    }
    
    public static function GetMine(\Model\Game\Character $c) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`    = :cid"
                . " order by"
                . "     `instance`,"
                . "     `skill`",
                array(
                    "cid" => $c->id
                )) ;
    }
    
    public static function CountMyFull(\Model\Game\Character $char) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`    = :cid and"
                . "     `remain`       = 0"
                . " order by"
                . "     `instance`,"
                . "     `skill`",
                array(
                    "cid" => $char->id
                )) ;
        return $row === false ? 0 : intval($row["c"]) ;
    }
    
}
