<?php

namespace Answer\View\Game ;

class Ressource extends \Answer\View\Base {
    
    private $_character ;
    
    public function __construct($service, $character) {
        parent::__construct($service)  ;
        $this->_character = $character ;
    }

    public function getTplContent() {
        return ""
                . new \Answer\Widget\Game\Ressource\Equipment($this->_character, "card-1-2")
                . new \Answer\Widget\Game\Ressource\Inventory($this->_character, "card-1-2")
                . new \Answer\Widget\Game\Ressource\Parcel($this->_character, "card-1-2")
                . new \Answer\Widget\Game\Ressource\Food($this->_character, "card-1-2")
                . new \Answer\Widget\Game\Ressource\TradingRessource($this->_character, "card-1-2")
                . new \Answer\Widget\Game\Ressource\TradingSkill($this->_character, "card-1-2")
                
            ;
    }

}
