<?php

namespace Services\Game\Map ;

class Info extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        $char = $this->getCharacter() ;
        if (\Model\Stats\Game\Moves::GetFromCharacterCity($char, $this->city) !== null) {
            return new \Widget\Game\Map\InfoSeen($this->city, $this->getCharacter());
        } else {
            return new \Widget\Game\Map\InfoUnseen($this->city, $this->getCharacter());
        }
        
        
    }
    
    
}