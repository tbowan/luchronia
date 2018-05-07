<?php

namespace Services\Game\Ministry\System ;

class Current extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        return new \Answer\View\Game\Ministry\Dgap($this, $this->city, $this->getCharacter()) ;
    }
    
}
