<?php

namespace Model\Game\Forum ;

class Access extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "PUBLIC",
        1 => "CHARACTER",
        2 => "STATE",
        3 => "PREFECTURE",
        4 => "CITY",
        5 => "POLITIC",
        6 => "GOVERNMENT",
    ) ;
    
    public function getPrefix() {
        return "FORUM_RIGHT_" ;
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
        return $char->isCitizen($city) ;
    }
    
    public function political(\Model\Game\Character $char, \Model\Game\City $city) {
        $system = \Model\Game\Politic\System::LastFromCity($city) ;
        return $system->canManage($char) ;
    }
    
    public function government(\Model\Game\Character $char, \Model\Game\City $city) {
        return \Model\Game\Politic\Minister::isMinister($char, $city) ;
    }
    
    public function canRW($char, Category $cat) {
        $city = $cat->instance->city ;
        switch($this->getId()) {
            case 0 :
                return true ;
            case 1 :
                return $char !== null ;
            case 2 :
                return $this->sameNation($char, $city) || Moderator::isModerator($char, $cat) ;
            case 3 :
                return $this->samePrefecture($char, $city) || Moderator::isModerator($char, $cat) ;
            case 4 :
                return $this->sameCity($char, $city) || Moderator::isModerator($char, $cat) ;
            case 5 :
                return $this->political($char, $city) || Moderator::isModerator($char, $cat) ;
            case 6 :
                return $this->government($char, $city) || Moderator::isModerator($char, $cat) ;
        }
    }
    
}
