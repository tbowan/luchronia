<?php

namespace Model\Forum ;

class Thread extends \Quantyl\Dao\BddObject {
 
  
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
    
    // Messages
    
    public function getPost() {
        return Post::GetFromThread($this) ;
    }
    
    public function countPost() {
        return Post::CountFromThread($this) ;
    }
    
    public function getLastPost() {
        return Post::GetLastFromThread($this) ;
    }
    
    // Survey
    
    public function getChoices() {
        return Choice::GetFromThread($this) ;
    }
    
    // Parser
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "category" :
            case "author" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "category" :
                return Category::createFromId($value) ;
            case "author" :
                return \Model\Game\Character::createFromId($value) ;
            default:
                return $value ;
        }
    }

    public static function getNameField() {
        return "title" ;
    }   
    
    public function getName() {
        $field = static::getNameField() ;
        return $this->$field ;
    }
    
    // Get some sets
    
    public static function GetFromCategory(Category $cat) {
        
        $query = "select t.id as id"
                . " from `" . static::getTableName() . "` as t"
                . " inner join ("
                    . " select thread, max(date) as date"
                    . " from `" . Post::getTableName() . "`"
                    . " group by thread"
                    . ") as m"
                    . " on m.thread = t.id"
                . " where category = :cid"
                . " order by pinned desc, m.date desc" ;
        
        return static::getResult($query, array("cid" => $cat->getId())) ;
    }
    
    
    public static function CountFromCategory(Category $cat) {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where `category` = :cid" ;
        
        $row = static::getSingleRow($query, array ("cid" => $cat->getId())) ;
        return intval($row["c"]) ;
    }
    
    public static function GetUnread($since) {
        return self::getResult(""
                . " select t.*, max(date) as date"
                . " from"
                . "   `" . static::getTableName() . "` as t,"
                . "   `" .   Post::getTableName() . "` as p"
                . " where"
                . "     t.id = p.thread and"
                . "     p.date > :since"
                . " group by t.id"
                . " order by date desc",
                array("since" => $since)) ;
    }
    
    public static function GetMine(\Model\Game\Character $author) {
        return self::getResult(""
                . " select t.*, max(date) as date"
                . " from"
                . "   `" . static::getTableName() . "` as t,"
                . "   `" .   Post::getTableName() . "` as p"
                . " where"
                . "     t.id = p.thread and"
                . "     p.author = :aid"
                . " group by t.id"
                . " order by date desc",
                array("aid" => $author->id)) ;
    }
    
    public static function GetFollowedBy(\Model\Game\Character $c) {
        $query = "select thread.*"
                . " from"
                . "     `" . Follow::getTableName() . "` as follow,"
                . "     `" . static::getTableName() . "` as thread"
                . " inner join ("
                . "     select thread, max(date) as date"
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
