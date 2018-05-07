<?php

namespace Model\Game\Post ;

class Type extends \Quantyl\Dao\AbstractEnum {
    
    
    protected static $_enumeration = array (
        0 => "NONE",
        1 => "INBOX" ,
        2 => "OUTBOX",
    ) ;
    
    // TODO
    public function getPrefix() {
        return "MAILBOX_TYPE_" ;
    }
    
}
