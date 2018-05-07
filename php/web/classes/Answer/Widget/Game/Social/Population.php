<?php

namespace Answer\Widget\Game\Social ;

class Population extends LongList {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city, $classes = "") {
        $this->_city = $city ;
        
        parent::__construct(
                \I18n::POPULATION(),
                "",
                "",
                $classes);
    }
    
    public function getList() {
        return \Model\Game\Character::GetPopulation($this->_city) ;
    }

}
