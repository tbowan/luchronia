<?php

namespace Services\Game\Building ;

class OutSide extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        $char = $this->getCharacter() ;
        $city = $this->city ;
        $mini = \Model\Game\Politic\Ministry::GetByName("Commerce") ;
        
        $admin = \Model\Game\Politic\Minister::hasPower($char, $city, $mini) ;
        
        return new \Answer\View\Game\Building\OutSide($this, $char, $city, $admin) ;
    }
    
}
