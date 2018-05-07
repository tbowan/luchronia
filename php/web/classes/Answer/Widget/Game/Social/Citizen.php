<?php

namespace Answer\Widget\Game\Social ;

class Citizen extends LongList {
    
    private $_city ;
    private $_isminister ;
    
    public function __construct(\Model\Game\City $city, $isminister, $classes = "") {
        $this->_city = $city ;
        $this->_isminister = $isminister ;
        
        parent::__construct(
                \I18n::CITIZENS(),
                "",
                "",
                $classes);
    }
    
    public function getList() {
        return \Model\Game\Character::GetFromCitizenship($this->_city) ;
    }

    public function getLinks($character) {
        
        $citizenship = \Model\Game\Citizenship::GetCitizenship($character, $this->_city) ;
        
        $res = parent::getLinks($character);
        if ($this->_isminister) {
            $res .= new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship/Details?citizenship={$citizenship->id}", \I18n::MANAGE()) ;
        }
        return $res ;
    }
    
}
