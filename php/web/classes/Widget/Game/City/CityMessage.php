<?php

namespace Widget\Game\City ;

use Model\Game\City;
use Quantyl\Answer\Widget;

class CityMessage extends Widget {
    
    private $_city ;
    private $_character ;
    
    public function __construct(City $city, \Model\Game\Character $c) {
        $this->_city = $city ;
        $this->_character = $c ;
    }

    public function getContent() {
        
        $res = "<h2>" . \I18n::CITY_MESSAGE() . "</h2>" ;
    
        $hasth = false ;
        foreach ($this->_city->getTownHalls() as $instance) {
            $hasth = true ;
            $townhall = \Model\Game\Building\TownHall::GetFromInstance($instance) ;
            $res .= "<h3>" . $townhall->name . "</h3>" ;
            $res .= $townhall->welcome ;
        }
        
        if (! $hasth && $this->_city->equals($this->_character->position)) {
            $res .= \I18n::CITY_ADD_TOWNHALL($this->_city->id) ;
        }
        
        return $res ;
    
    }
    
    
}
