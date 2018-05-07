<?php

namespace Model\Game\Politic ;

class Support extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "revolution" :
                return Revolution::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "revolution" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function GetFromCharacterAndCity(\Model\Game\Character $char, \Model\Game\City $city) {
        return static::getSingleResult(""
                . " select support.*"
                . " from"
                . "     `" . Revolution::getTableName() . "` as revolution,"
                . "     `" . Support::getTableName() . "` as support,"
                . "     `" . System::getTableName() . "` as system"
                . " where"
                . "     revolution.id       = support.revolution    and"
                . "     system.id           = revolution.system     and"
                . "     system.city         = :city                 and"
                . "     support.`character` = :char",
                array(
                    "city" => $city->id,
                    "char" => $char->id
                )) ;
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  `character` = :cid",
                array("cid" => $c->id)) ;
    }
    
    public static function CountFromRevolution(Revolution $r) {
        $row = static::getSingleRow(
                "select count(*) as c"
                . " from `" . self::getTableName() . "` as s"
                . " join `" . \Model\Game\Character::getTableName() . "` as c"
                . " where"
                . "  s.`character` = c.id and"
                . "  not c.locked and"
                . "  s.`revolution` = :rid",
                array("rid" => $r->id)) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function isSupporting(Revolution $r, \Model\Game\Character $c) {
        $res = self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `revolution` = :rid and"
                . "     `character`  = :cid",
                array(
                    "cid" => $c->id,
                    "rid" => $r->id
                )) ;
        return $res > 0 ;
    }
    
    public static function getSupporting(Revolution $r, \Model\Game\Character $c) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `revolution` = :rid and"
                . "     `character`  = :cid",
                array(
                    "cid" => $c->id,
                    "rid" => $r->id
                )) ;
    }
    
    public static function doSupport(Revolution $r, \Model\Game\Character $c) {
        
        $last_support    = self::getSupporting($r, $c) ;
        
        if ($last_support == null) {
            $last_revolution = null ;
            $last_support    = Support::createFromValues(array(
                "revolution" => $r,
                "character" => $c
            )) ;
        } else {
            $last_revolution = $last_support->revolution ;
            $last_support->revolution = $r ;
            $last_support->update() ;
        }
        
        $r->checkThreshold() ;
        
        if ($last_revolution !== null) {
            $last_revolution->checkThreshold() ;
        }
    }
    
    public static function doUnSupport(Revolution $r, \Model\Game\Character $c) {
        
        $last_support    = self::getSupporting($r, $c) ;
        if ($last_support === null || ! $r->equals($last_support->revolution)) {
            // Char support other revolution, nothing to do
            return ;
        }
        
        $last_support->delete() ;
        $r->checkThreshold() ;
        
    }


}
