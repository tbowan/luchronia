<?php

namespace Model\Game\Politic ;

class Minister extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "government" :
                return Government::GetById($value) ;
            case "ministry" :
                return Ministry::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "government" :
            case "ministry" :
            case "character" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    // Get some sets
    
    public static function getMinisterCharacter(Government $g) {
        return \Model\Game\Character::getResult(
                "select c.*"
                . " from"
                . "     `" . self::getTableName() . "` as m,"
                . "     `" . \Model\Game\Character::getTableName() . "` as c"
                . " where"
                . "     c.id = m.`character` and"
                . "     m.`government` = :gid"
                . " group by c.id",
                array("gid" => $g->id)) ;
    }

    public static function getMinistries(Government $g, \Model\Game\Character $c) {
        return Ministry::getResult(
                "select y.*"
                . " from"
                . "     `" . self::getTableName() . "` as e,"
                . "     `" . Ministry::getTableName() . "` as y"
                . " where"
                . "     e.`character`  = :cid and"
                . "     e.`government` = :gid and"
                . "     e.ministry     = y.id"
                . " group by y.id",
                array(
                    "gid" => $g->id,
                    "cid" => $c->id
                        )) ;
    }
    
    public static function getMinisters(Government $g) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `government` = :gid"
                . " order by `ministry`",
                array("gid" => $g->id)) ;
    }
    
    public static function getMinistersForMinistry(Government $g, Ministry $ministry) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `government` = :gid and"
                . "     `ministry`   = :mid",
                array(
                    "gid" => $g->id,
                    "mid" => $ministry->id
                        )) ;
    }
    
    public static function GetMinister(\Model\Game\Character $c, Government $g, Ministry $m) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`  = :cid and"
                . "     `government` = :gid and"
                . "     `ministry`   = :mid",
                array(
                    "cid" => $c->id,
                    "gid" => $g->id,
                    "mid" => $m->id
                )) ;
    }
    
    public static function addMinister(\Model\Game\Character $c, Government $g, Ministry $m) {
        $last = static::GetMinister($c, $g, $m) ;
        if ($last == null) {
            $last = static::createFromValues(array(
                "ministry" => $m,
                "government" => $g,
                "character" => $c
            )) ;
        }
        return $last ;
    }
    
    public static function hasPower(\Model\Game\Character $char, \Model\Game\City $city, Ministry $ministry) {
        
        if (! $city->hasTownHall()) {
            return true ;
        }
        
        $system = System::LastFromCity($city) ;
        $anarchy = SystemType::Anarchy() ;
        if ($system->type->equals($anarchy)) {
            return $char->isCitizen($city) ;
        }
        
        $government = Government::CurrentFromSystem($system) ;
        if ($government == null) {
            return false ;
        }
        
        $minister = self::GetMinister($char, $government, $ministry) ;
        return $minister !== null ;
    }
    
    public static function isMinister(\Model\Game\Character $char, \Model\Game\City $city) {
        $system = System::LastFromCity($city) ;
        $anarchy = SystemType::Anarchy() ;
        if ($system->type->equals($anarchy)) {
            return $char->isCitizen($city) ;
        }
        
        $government = Government::CurrentFromSystem($system) ;
        if ($government == null) {
            return false ;
        }
        
        $row = static::getSingleRow(
                "select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`  = :cid and"
                . "     `government` = :gid",
                array(
                    "cid" => $char->id,
                    "gid" => $government->id
                )) ;
        
        return $row !== false ;
    }

}
