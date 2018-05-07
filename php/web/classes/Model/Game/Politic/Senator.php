<?php

namespace Model\Game\Politic ;

class Senator extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "senate" :
                return Senate::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "senate" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

    public static function GetActive(Senate $senate) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `senate` = :sid and"
                . "     isnull(`end`)",
                array(
                    "sid"  => $senate->id
                    )) ;
    }
    
    public static function GetActiveFromCharacter(Senate $senate, \Model\Game\Character $char) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `senate` = :sid and"
                . "     isnull(`end`) and"
                . "     `character` = :cid",
                array(
                    "sid"  => $senate->id,
                    "cid"  => $char->id
                    )) ;
    }
    
    public static function CanManage(Senate $senate, \Model\Game\Character $char) {
        $temp = self::GetActiveFromCharacter($senate, $char) ;
        return $temp != null && $temp->start != null ;
    }
    
    public static function CountFromSenate(Senate $s) {
        return self::getCount(""
                . " select count(*) as c"
                . " from"
                . "     `" . self::getTableName() . "` as senator"
                . " where"
                . "     senator.senate = :sid and"
                . "     not isnull(senator.start) and"
                . "     isnull(senator.end)",
                array("sid" => $s->id)) ;
    }
    
    public static function GetOut(Senate $s) {
        
        
        $total = max(1, self::CountFromSenate($s) -  1) ;
        $threshold = $total * $s->revocation ;
        
        $senators = self::getResult(""
                . " select senator.*"
                . " from"
                . "     `" . self::getTableName() . "` as senator"
                . " join ("
                . "     select"
                . "         count(*) as c,"
                . "         target as senator"
                . "     from"
                . "         `" . Friend::getTableName() . "` as fr,"
                . "         `" . self::getTableName() . "` as se"
                . "     where"
                . "         se.senate = :sid and"
                . "         fr.source = se.id and"
                . "         not isnull(se.start) and"
                . "         isnull(se.end) and"
                . "         fr.value < 0"
                . "     group by fr.target"
                . "     ) as opponents"
                . " on"
                . "     opponents.senator = senator.id"
                . " where"
                . "     opponents.c > :t and"
                . "     not isnull(senator.start) and"
                . "     isnull(senator.end)",
                array("sid" => $s->id, "t" => $threshold)) ;
        
        $outs = 0 ;
        foreach ($senators as $sen) {
            $sen->end = time() ;
            $sen->update() ;
            $outs++ ;
        }
        
        if ($outs > 0) {
            $outs += self::GetOut($s) ;
        }
        
        return $outs ;
    }
    
    public static function GetIn(Senate $s) {
        $total = self::CountFromSenate($s) ;
        $threshold = $total * $s->admission ;
        
        $senators = self::getResult(""
                . " select senator.*"
                . " from"
                . "     `" . self::getTableName() . "` as senator"
                . " join ("
                . "     select"
                . "         count(*) as c,"
                . "         target as senator"
                . "     from"
                . "         `" . Friend::getTableName() . "` as fr,"
                . "         `" . self::getTableName() . "` as se"
                . "     where"
                . "         se.senate = :sid and"
                . "         fr.source = se.id and"
                . "         not isnull(se.start) and"
                . "         isnull(se.end) and"
                . "         fr.value > 0"
                . "     group by fr.target"
                . "     ) as supports"
                . " on"
                . "     supports.senator = senator.id"
                . " where"
                . "     supports.c > :t and"
                . "     isnull(senator.start)",
                array("sid" => $s->id, "t" => $threshold)) ;
        
        $ins = 0 ;
        foreach ($senators as $sen) {
            $sen->start = time() ;
            $sen->update() ;
            $ins++ ;
        }
    }
    
}
