<?php

namespace Services\Game\Map ;

class City extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        
        return new \Answer\Object\Map\City($this->id) ;
        
        
    }
    
    
}