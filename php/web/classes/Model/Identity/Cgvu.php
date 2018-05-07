<?php

namespace Model\Identity ;

class Cgvu extends \Quantyl\Dao\BddObject {
    
    public function getAfter($time = null) {
        
        if ($time == null) {
            $time = time() ;
        }
        
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where inserted > :t",
                array("t" => $time)) ;
        
    }
    
    public static function getLast() {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " order by inserted desc"
                . " limit 1",
                array()) ;
    }
    
    public function isLast() {
        $last = self::getLast() ;
        return $this->equals($last) ;
    }
    
}
