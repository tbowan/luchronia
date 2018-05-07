<?php

namespace Model\Game\Politic ;

class Friend extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "target" :
                return Senator::GetById($value) ;
            case "source" :
                return Senator::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "target" :
            case "source" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function CountSupport(Senator $s) {
        return self::getCount(""
                . " select count(*) as c"
                . " from"
                . "     `" . self::getTableName() . "` as friend,"
                . "     `" . Senator::getTableName() . "` as senator"
                . " where"
                . "     senator.id = friend.source and"
                . "     not isnull(senator.start) and"
                . "     isnull(senator.end) and"
                . "     `target` = :sid and"
                . "     `value` > 0",
                array("sid" => $s->id)) ;
    }
    
    public static function CountOpponent(Senator $s) {
        return self::getCount(""
                . " select count(*) as c"
                . " from"
                . "     `" . self::getTableName() . "` as friend,"
                . "     `" . Senator::getTableName() . "` as senator"
                . " where"
                . "     senator.id = friend.source and"
                . "     not isnull(senator.start) and"
                . "     isnull(senator.end) and"
                . "     `target` = :sid and"
                . "     `value` < 0",
                array("sid" => $s->id)) ;
    }
    
    public static function GetSupport(Senator $target, Senator $source) {
        return self::getSingleResult(""
                . " select *"
                . " from"
                . "     `" . self::getTableName() . "` as friend"
                . " where"
                . "     target = :tid and"
                . "     source = :sid",
                array(
                    "sid" => $source->id,
                    "tid" => $target->id
                        )) ;
    }
    
    public static function coopt(Senator $source, \Model\Game\Character $target, $value = 1) {
        $t = Senator::GetActiveFromCharacter($source->senate, $target) ;
        if ($t == null) {
            $t = Senator::createFromValues(array(
                "senate" => $source->senate,
                "character" => $target
            )) ;
        }
        return self::Support($t, $source, $value) ;
    }
    
    public static function Support(Senator $target, Senator $source, $value = +1) {
        
        if ($target->equals($source)) {
            return ;
        }
        
        $temp = self::GetSupport($target, $source) ;
        if ($temp == null) {
            $temp = Friend::createFromValues(array(
                "source" => $source,
                "target" => $target,
                "value" => $value
            )) ;
        } else {
            $temp->value = $value ;
            $temp->update() ;
        }

        
    }

}
