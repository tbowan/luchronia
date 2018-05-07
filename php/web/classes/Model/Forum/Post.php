<?php

namespace Model\Forum ;

class Post extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "thread" :
            case "author" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "thread" :
                return Thread::createFromId($value) ;
            case "author" :
                return \Model\Game\Character::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    // Get Some set
    
    // From Thread
    
    public static function GetFromThread(Thread $th) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid"
                . " order by `date`" ;
        
        return static::getResult($query, array("tid" => $th->getId())) ;
    }
    
    public static function CountFromThread(Thread $th) {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid"
                . " order by `date`" ;
        
        $row = static::getSingleRow($query, array("tid" => $th->getId())) ;
        return intval($row["c"]) ;
    }
    
    public static function GetLastFromThread(Thread $th) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where thread = :tid"
                . " order by `date` desc"
                . " limit 1" ;
        
        return static::getSingleResult($query, array("tid" => $th->getId())) ;
    }
    
    // From Category 
    
    public static function CountFromCategory(Category $cat) {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "` as m"
                . " join `" . Thread::getTableName() . "` as t"
                . "  on t.id = m.thread"
                . " where"
                . "  t.`category` = :cid" ;
        
        $row = static::getSingleRow($query, array ("cid" => $cat->getId())) ;
        return intval($row["c"]) ;
    }
    
    public static function getLastFromCategory(Category $cat) {
        $query = "select"
                . "    m.id as id,"
                . "    m.thread as thread,"
                . "    m.author as author,"
                . "    m.date as date"
                . " from `" . static::getTableName() . "` as m"
                . " join `" . Thread::getTableName() . "` as t"
                . "  on t.id = m.thread"
                . " where"
                . "  t.`category` = :cid"
                . " order by m.`date` desc"
                . " limit 1" ;
        
        return static::getSingleResult($query, array ("cid" => $cat->getId())) ;
    }
    
    public static function CountSince($since) {
        return self::getCount(""
                . " select count(id) as c"
                . " from `" . self::getTableName() . "`"
                . " where `date` > :since",
                array("since" => $since)) ;
    }
    
    public static function CountForAuthor(\Model\Game\Character $author) {
        return self::getCount(""
                . " select count(id) as c"
                . " from `" . self::getTableName() . "`"
                . " where `author` = :aid",
                array("aid" => $author->id)) ;
    }
    
}
