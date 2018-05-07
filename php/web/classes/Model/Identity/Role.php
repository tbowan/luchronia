<?php

namespace Model\Identity ;

class Role extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "user" :
                return User::GetById($value) ;
            case "group" :
                return Group::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "user" :
            case "group" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function acl($u, $g) {
        return ($g == null) || (($u != null) && static::hasRole($u, $g)) ;
    }
    
    public static function hasRole(User $u, Group $g) {
        $row = static::getSingleRow(
                "select true"
                . " from `".self::getTableName()."`"
                . " where"
                . " `group` = :group and"
                . " `user` = :user",
                array(
                    "user" => $u->getId(),
                    "group" => $g->getId()
                )) ;
        return $row !== false ;
    }
    
    public static function GetFromUser(User $u) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `user` = :u ",
                array("u" => $u->getId())
                ) ;
    }
    
    public static function GetFromGroup(Group $g) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `group` = :g ",
                array("g" => $g->getId())
                ) ;
    }
}
