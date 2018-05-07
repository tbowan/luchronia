<?php

namespace Model\Ecom\Allopass;

class Code extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "product" :
                return \Model\Ecom\Allopass\Product::GetById($value) ;
            case "quanta" :
                return \Model\Ecom\Quanta::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "product" :
            case "quanta" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function TotalSince($since) {
        return self::getCount(""
                . " select count(code.id) as total"
                . " from"
                . "     `" . self::getTableName() . "` as code,"
                . "     `" . \Model\Ecom\Quanta::getTableName() . "` as quanta"
                . " where"
                . "     code.quanta = quanta.id and"
                . "     quanta.`timestamp` > :since",
                array("since" => $since),
                "total") ;
    }
    
    public static function Stats($begin, $end, $steps) {
        return self::getStatement(""
                . " select"
                . "     sum(code.id) as total,"
                . "     mod(quanta.`timestamp` - :begin, :mod) as `t`"
                . " from"
                . "     `" . self::getTableName() . "` as code,"
                . "     `" . \Model\Ecom\Quanta::getTableName() . "` as quanta"
                . " where"
                . "     code.quanta = quanta.id and"
                . "     quanta.`timestamp` > :begin and"
                . "     quanta.`timestamp` < :end",
                 " group by"
                . "     `t`",
                array(
                    "begin" => $begin,
                    "end"   => $end,
                    "mod"   => $steps
                    )) ;
    }
    
    public static function GetFromCode(\Model\Identity\User $user, $code) {
        return self::getSingleResult(""
                . " select"
                . "     code.*"
                . " from"
                . "     `" . self::getTableName() . "` as code,"
                . "     `" . \Model\Ecom\Quanta::getTableName() . "` as quanta"
                . " where"
                . "     code.quanta = quanta.id and"
                . "     quanta.user = :uid and"
                . "     code.code   = :code",
                array(
                    "code" => $code,
                    "uid"  => $user->getId()
                    )) ;
    }
    
    public static function GetFromTransId($trxid) {
        return self::getSingleResult(""
                . " select"
                . "     *"
                . " from"
                . "     `" . self::getTableName() . "`"
                . " where"
                . "     trxid   = :trxid",
                array(
                    "trxid" => $trxid
                    )) ;
    }
    
}
