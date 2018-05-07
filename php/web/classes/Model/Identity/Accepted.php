<?php

namespace Model\Identity ;

class Accepted extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "user" :
                return User::GetById($value) ;
            case "cgvu" :
                return Cgvu::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "user" :
            case "cgvu" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromUser(User $u) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `user` = :uid",
                array("uid" => $u->getId())
            );
    }
    
    public static function getFromCgvu(Cgvu $cgvu) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `cgvu` = :cid",
                array("cid" => $cgvu->getId())
            );
    }
    
    public static function getAccepted(User $u, Cgvu $cgvu) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `user` = :uid and `cgvu` = :cid",
                array("uid" => $u->getId(), "cid" => $cgvu->getId())
            );
    }
    
}
