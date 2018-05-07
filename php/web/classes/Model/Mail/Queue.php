<?php

namespace Model\Mail ;

class Queue extends \Quantyl\Dao\BddObject {
    
    public static function getFirst() {
        return self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " limit 1", array()) ;
    }
    
    public static function Queue($from, $to, $subject, $msg) {
            
        return self::createFromValues(array(
            "from"      => $from,
            "to"        => $to,
            "subject"   => $subject,
            "content"   => $msg
        )) ;
    }
    
}
