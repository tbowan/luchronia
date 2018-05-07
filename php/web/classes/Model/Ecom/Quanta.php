<?php

namespace Model\Ecom ;

class Quanta extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "user" :
                return \Model\Identity\User::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "user" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }

    // Get som sets

    // get some stats
    
    public static function TotalSince($since) {
        return self::getCount(""
                . " select sum(amount) as total"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `timestamp` > :since",
                array("since" => $since),
                "total") ;
    }
    
    public static function Stats($begin, $end, $steps) {
        return self::getStatement(""
                . " select"
                . "     sum(amount) as total,"
                . "     mod(`timestamp` - :begin, :mod) as `t`"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `timestamp` > :begin and"
                . "     `timestamp` < :end"
                . " group by"
                . "     `t`",
                array(
                    "begin" => $begin,
                    "end"   => $end,
                    "mod"   => $steps
                    )) ;
    }
}
