<?php

namespace Model\Game\Forum ;

class Thread extends \Quantyl\Dao\BddObject {
    
    use \Model\Name ;
    
    public function getImages($class) {
        $icons = "" ;
        if ($this->pinned) {
            $icons .= "<img src=\"/Media/icones/base/star.png\" class=\"$class\"/>" ;
        }

        if ($this->closed) {
            $icons .= "<img src=\"/Media/icones/base/lock.png\" class=\"$class\"/>" ;
        }
        return $icons ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "category" :
                return Category::GetById($value) ;
            case "author" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "category" :
            case "author" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromCategory(Category $c) {
        
        $query = "select thread.*"
                . " from `" . static::getTableName() . "` as thread"
                . " inner join ("
                    . " select thread, max(pub_date) as date"
                    . " from `" . Post::getTableName() . "`"
                    . " group by thread"
                    . ") as post"
                    . " on post.thread = thread.id"
                . " where category = :cid"
                . " order by pinned desc, post.date desc" ;
        
        return self::getResult(
                $query,
                array("cid" => $c->getId())) ;
    }

    public static function getNameField() {
        return "title" ;
    }
    
    public static function CountFromCategory(Category $c) {
        
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "` as thread"
                . " where category = :cid" ;
        
        $row = self::getSingleRow(
                $query,
                array("cid" => $c->getId())) ;
        
        return ($row === false ? 0 : intval($row["c"])) ;
    }
    
    public static function GetFollowedBy(\Model\Game\Character $c) {
        $query = "select thread.*"
                . " from"
                . "     `" . Follow::getTableName() . "` as follow,"
                . "     `" . static::getTableName() . "` as thread"
                . " inner join ("
                . "     select thread, max(pub_date) as date"
                . "     from `" . Post::getTableName() . "`"
                . "     group by thread"
                . "     ) as post"
                . " on post.thread = thread.id"
                . " where"
                . "     thread.id = follow.thread and"
                . "     follow.character = :cid"
                . " order by pinned desc, post.date desc" ;
        
        return self::getResult(
                $query,
                array("cid" => $c->getId())) ;
    }
}
