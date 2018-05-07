<?php

namespace Services\Game\City ;

class Citizen extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getView() {
        
        $isMinister = \Model\Game\Politic\Minister::hasPower($this->getCharacter(), $this->city, \Model\Game\Politic\Ministry::GetByName("Homeland")) ;
        
        return new \Answer\Widget\Game\Social\Citizen($this->city, $isMinister);
    }
    
    
}
