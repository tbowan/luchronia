<?php

namespace Model\Ecom\Code;

class Apply extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "bonus" :
                return \Model\Ecom\Code\Bonus::GetById($value) ;
            case "quanta" :
                return \Model\Ecom\Quanta::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "bonus" :
            case "quanta" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function CountTotalUses(Bonus $b) {
        return self::getCount(""
                . " select count(id) as total"
                . " from"
                . "     `" . self::getTableName() . "` as apply"
                . " where"
                . "     apply.bonus  = :b",
                array(
                    "b" => $b->id
                ),
                "total") ;
    }
    
    public static function CountUserUses(\Model\Identity\User $u, Bonus $b) {
        return self::getCount(""
                . " select count(*) as total"
                . " from"
                . "     `" . self::getTableName() . "` as apply,"
                . "     `" . \Model\Ecom\Quanta::getTableName() . "` as quanta"
                . " where"
                . "     apply.quanta = quanta.id and"
                . "     quanta.user  = :u and"
                . "     apply.bonus  = :b",
                array(
                    "u" => $u->id,
                    "b" => $b->id
                ),
                "total") ;
    }
    
}
