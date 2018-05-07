<?php

namespace Model\Game\Skill ;

class Research extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "type" :
                return \Model\Game\Building\Type::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "type" :
            case "character" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :cid",
                array(
                    "cid" => $c->id
                )) ;
    }
    
    public static function GetFromType(\Model\Game\Building\Type $t) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `type` = :tid",
                array(
                    "tid" => $t->id
                )) ;
    }
    
    public static function GetFromCharAndType(\Model\Game\Character $c, \Model\Game\Building\Type $t) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `type` = :tid and"
                . "     `character` = :cid",
                array(
                    "tid" => $t->id,
                    "cid" => $c->id
                )) ;
    }
    
    public static function doResearch(\Model\Game\Character $char, \Model\Game\Building\Type $type, $amount) {
        $res = self::GetFromCharAndType($char, $type) ;
        if ($res === null) {
            $res = self::createFromValues(array(
                "type" => $type,
                "character" => $char
            )) ;
        }
        $res->amount += $amount ;
        $res->update() ;
        return $res ;
    }
    
}
