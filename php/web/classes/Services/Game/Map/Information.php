<?php

namespace Services\Game\Map ;

class Information extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        return new \Answer\View\Game\Map\Information($this->id, $this->getCharacter()) ;
    }
}
