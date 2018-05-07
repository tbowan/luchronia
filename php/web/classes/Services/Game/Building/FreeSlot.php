<?php

namespace Services\Game\Building ;

class FreeSlot extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        
        $isadmin = \Model\Game\Politic\Minister::hasPower(
                $this->getCharacter(),
                $this->city,
                \Model\Game\Politic\Ministry::GetByName("Development")
                ) ;
        
        return new \Answer\View\Game\Building\FreeSlot($this, $this->city, $isadmin) ;
        
    }
    
}
