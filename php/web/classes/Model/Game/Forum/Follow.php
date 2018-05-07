<?php

namespace Model\Game\Forum ;

class Follow extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "thread" :
                return Thread::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "last_post" :
                return Post::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "thread" :
            case "character" :
            case "last_post" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromThread(Thread $t) {
        return self::getResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where thread = :tid",
                array("tid" => $t->getId())) ;
    }
    
    public static function CountFromThread(Thread $t) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where thread = :tid",
                array("tid" => $t->getId())) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function getFromCharacter(\Model\Game\Character $c) {
        return self::getResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where `character` = :cid",
                array("cid" => $c->getId())) ;
    }
    
    public static function CountFromCharacter(\Model\Game\Character $c) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where `character` = :cid",
                array("cid" => $c->getId())) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function getFollowing(Thread $t, \Model\Game\Character $c) {
        return self::getSingleResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where `character` = :cid and `thread` = :tid",
                array("tid" => $t->getId(), "cid" => $c->getId())) ;
    }
    
    public static function isFollowing(Thread $t, \Model\Game\Character $c) {
        $temp = self::getFollowing($t, $c) ;
        return $temp != null ;
    }
    
    public static function notify(Thread $t, \Model\Game\Character $c, Post $p) {
        $temp = self::getFollowing($t, $c) ;
        if ($temp != null) {
            $temp->last_post = $p ;
            $temp->update() ;
        }
    }
    
    public static function GetNew(\Model\Game\Character $me) {
        return self::getSingleResult(""
                . " select follow.*"
                . " from"
                . "     `" . static::getTableName() . "` as follow,"
                . "     `" . Post::getTableName()   . "` as post"
                . " where"
                . "     `follow`.thread      = `post`.thread and"
                . "     `follow`.last_post   < `post`.id     and"
                . "     `follow`.`character` = :cid"
                . " group by `follow`.id",
                array("cid" => $me->getId())) ;
    }
    
    public static function CountNew(\Model\Game\Character $me) {
        $row = self::getSingleRow(""
                . " select count(*) as c"
                . " from"
                . "     `" . static::getTableName() . "` as follow,"
                . "     `" . Post::getTableName()   . "` as post"
                . " where"
                . "     `follow`.thread      = `post`.thread and"
                . "     `follow`.last_post   < `post`.id     and"
                . "     `follow`.`character` = :cid"
                . " group by `follow`.id",
                array("cid" => $me->getId())) ;
        return ($row === false ? 0 : intval($row["c"])) ;
    }
   
     public static function getFollowers(Thread $t) {
        return self::getResult(""
                . " select *"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid",
                array("tid" => $t->getId())) ;
    }    
    
}
