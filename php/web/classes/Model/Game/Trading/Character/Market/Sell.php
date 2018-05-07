<?php

namespace Model\Game\Trading\Character\Market ;

class Sell extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "market" :
                return \Model\Game\Building\Instance::GetById($value) ;
            case "ressource" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "market" :
            case "ressource" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getOrderForItem(\Model\Game\Building\Instance $market, \Model\Game\Ressource\Item $ressource) {
        return self::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `market` = :mid and `ressource` = :rid and `remain` > 0"
                . " order by `price`",
                array(
                    "mid" => $market->id,
                    "rid" => $ressource->id
                )) ;
    }
    
    public static function getRessources(\Model\Game\Building\Instance $i) {
        return self::getStatement(
                "   select"
                . "     ressource as item,"
                . "     min(price) as best,"
                . "     max(price) as worst,"
                . "     sum(remain) as quantity,"
                . "     count(id)  as number"
                . " from `" . self::getTableName() . "`"
                . " where `market` = :mid and `remain` > 0"
                . " group by ressource",
                array(
                    "mid" => $i->id
                )) ;
    }
    
    public static function GetFromCharacter(\Model\Game\Building\Instance $market, \Model\Game\Character $char) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `market`    = :mid and"
                . "     `character` = :cid",
                array(
                    "mid" => $market->id,
                    "cid" => $char->id
                )) ;
    }
    
    public static function GetMySells(\Model\Game\Character $char) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :cid"
                . " order by"
                . "     `market`,"
                . "     `ressource`",
                array(
                    "cid" => $char->id
                )) ;
    }
    
    public static function CountMyFull(\Model\Game\Character $char) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character` = :cid and"
                . "     `remain`    = 0"
                . " order by"
                . "     `market`,"
                . "     `ressource`",
                array(
                    "cid" => $char->id
                )) ;
        return $row === false ? 0 : intval($row["c"]) ;
    }
    
    public static function GetCountMarketAndCharacter(\Model\Game\Building\Instance $market, \Model\Game\Character $char) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `market`    = :mid and"
                . "     `character` = :cid",
                array(
                    "mid" => $market->id,
                    "cid" => $char->id
                )) ;
        return $row === false ? 0 : intval($row["c"]) ;
    }
    
}
