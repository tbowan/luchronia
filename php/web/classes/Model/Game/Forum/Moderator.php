<?php

namespace Model\Game\Forum ;

class Moderator extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "category" :
                return Category::GetById($value) ;
            case "moderator" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "category" :
            case "moderator" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromCategory(Category $c) {
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where category = :cid" ;
        
        return self::getResult(
                $query,
                array("cid" => $c->getId())) ;
    }
    
    public static function isModerator(\Model\Game\Character $mod, Category $c) {
        $row = self::getSingleRow(""
                . " select true"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `moderator` = :mid and"
                . "     `category`  = :cid",
                array(
                    "mid" => $mod->id,
                    "cid" => $c->id
                )) ;
        
        return $row !== false ;
    }
    
}
