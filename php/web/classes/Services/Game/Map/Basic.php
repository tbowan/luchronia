<?php

namespace Services\Game\Map ;

class Basic extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\IdMult(\Model\Game\City::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        return new \Answer\View\Game\Map\Basic($this->id, $this->getCharacter()) ;
    }
}
