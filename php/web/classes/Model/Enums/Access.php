<?php

namespace Model\Enums ;

class Access extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "PUBLIC",        // for everyone
        1 => "CONFIDENTIAL" , // friends / Citizen only
        2 => "SECRET",        // me      / government only
    ) ;
    
    
    public function hasCharacterAccess(\Model\Game\Character $char, $me) {
        if ($me === null) {
            return false ;
        }
        
        switch($this->getId()) {
            case 0 : // public
                return true ;
            case 1 : // confidential
                return \Model\Game\Social\Friend::areFriends($char, $me) ;
            case 2 :
                return $char->equals($me) ;
        }
    }
    
    public function getPrefix() {
        return "ACCESS_" ;
    }

}
