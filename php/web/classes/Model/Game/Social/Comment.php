<?php

namespace Model\Game\Social ;

class Comment extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "author" :
                return \Model\Game\Character::GetById($value) ;
            case "post" :
                return \Model\Game\Social\Post::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "author" :
            case "post" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromPost(Post $p) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `post` = :p",
                array ("p" => $p->getId())
                ) ;
    }
    
}
