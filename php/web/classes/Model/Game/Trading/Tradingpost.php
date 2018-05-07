<?php

namespace Model\Game\Trading ;

class Tradingpost extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return \Model\Game\Building\Instance::GetById($value) ;
            case "trading" :
                return \Model\Game\Trading\Nation::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
            case "trading" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(\Model\Game\Building\Instance $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->getId())
                ) ;
    }
    
    public static function GetFromInstanceAndType(\Model\Game\Building\Instance $i, Type $t) {
        return static::getResult(
                "select o.*"
                . " from"
                . "     `" . self::getTableName() . "` as o,"
                . "     `" . Nation::getTableName() . "` as n"
                . " where"
                . "     o.`instance` = :iid and"
                . "     n.id         = o.trading and"
                . "     n.`type`     = :tid",
                array(
                    "iid" => $i->getId(),
                    "tid" => $t->getId()
                )) ;
    }
    
    public static function GetOrder(\Model\Game\Building\Instance $i, Nation $n) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `instance` = :iid and `trading` = :nid",
                array("iid" => $i->getId(), "nid" => $n->id)
                ) ;
    }
    
    public static function GetCountFromInstance(\Model\Game\Building\Instance $i) {
        $res = static::getSingleRow(
                "select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->getId())
                ) ;
        return ($res === false ? 0 : intval($res["c"])) ;
    }
    
}