<?php

namespace Model\Forum ;

class Choice extends \Quantyl\Dao\BddObject {
    
    public function getVotes() {
        return Vote::GetFromChoice($this) ;
    }
    
    public function getScore() {
        return Vote::CountFromChoice($this) ;
    }
    
    // Parser
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "thread" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "thread" :
                return Thread::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    // Get Some set
    
    public static function GetFromThread(Thread $th) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid" ;
        
        return static::getResult($query, array("tid" => $th->getId())) ;
    }
    
    public static function CountFromThread(Thread $th) {
        $query = "select count(*) as c"
                . " from `" . static::getTableName() . "`"
                . " where `thread` = :tid" ;
        
        return static::getCount($query, array("tid" => $th->getId())) ;
    }
}
