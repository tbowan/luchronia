<?php

namespace Model\Enums ;

class Door extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "OPEN",        // access for all
        1 => "STATE" ,      // access for nationnality
        2 => "PREFECTURE",  // access for citizen of same prefecture only
        3 => "CITY",        // access for citizen only
    ) ;
    
    public function getPrefix() {
        return "WALL_DOOR_" ;
    }
    
    public function sameNation(\Model\Game\Character $char, \Model\Game\City $city) {
        if ($city->palace != null && $city->palace->equals($char->nationality) ) {
            return true ;
        } else {
            return $this->sameCity($char, $city) ;
        }
    }
    
    public function samePrefecture(\Model\Game\Character $char, \Model\Game\City $city) {
        if ($city->prefecture != null && $char->isCitizen($city->prefecture) ) {
            return true ;
        } else {
            return $this->sameCity($char, $city) ;
        }
    }
    
    public function sameCity(\Model\Game\Character $char, \Model\Game\City $city) {
        return $char->isCitizen(city) ;
    }
    
    public function canEnter(\Model\Game\Character $char, \Model\Game\City $city) {
        if (! $char->position->equals($city)) {
            return false ;
        }
        
        switch($this->getId()) {
            case 0 :
                return true ;
            case 1 :
                return $this->sameNation($char, $city) ;
            case 2 :
                return $this->samePrefecture($char, $city) ;
            case 3 :
                return $char->isCitizen($city) ;
        }
    }
    
}
