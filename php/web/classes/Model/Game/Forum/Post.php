<?php

namespace Model\Game\Forum ;

class Post extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "thread" :
                return Thread::GetById($value) ;
            case "pub_author" :
                return \Model\Game\Character::GetById($value) ;
            case "mod_author" :
                return ($value == null ? null : \Model\Game\Character::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "thread" :
            case "pub_author" :
            case "mod_author" :
                return $value->getId() ;
            case "mod_author" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public static function getFromThread(Thread $t) {
        
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where thread = :tid"
                . " order by pub_date" ;
        
        return self::getResult(
                $query,
                array("tid" => $t->getId())) ;
    }
    
    public static function CountFromThread(Thread $t) {
        
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where thread = :tid" ;
        
        $row = self::getSingleRow(
                $query,
                array("tid" => $t->getId())) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function LastFromThread(Thread $t) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid"
                . " order by pub_date desc"
                . " limit 1",
                array("tid" => $t->id)) ;
    }
    
    public static function LastFromCategory(Category $c) {
        $query = "select post.*"
                . " from"
                . "     `" .   self::getTableName() . "` as post,"
                . "     `" . Thread::getTableName() . "` as thread"
                . " where"
                . "     category = :cid and"
                . "     post.thread = thread.id"
                . " order by post.pub_date desc"
                . " limit 1" ;
        
        return self::getSingleResult(
                $query,
                array("cid" => $c->getId())) ;
    }
    
    public static function CountFromCategory(Category $c) {
        
        $query = "select count(*) as c"
                . " from"
                . "     `" .   self::getTableName() . "` as post,"
                . "     `" . Thread::getTableName() . "` as thread"
                . " where"
                . "     category = :cid and"
                . "     post.thread = thread.id" ;
        
        $row = self::getSingleRow(
                $query,
                array("cid" => $c->getId())) ;
        
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
}
