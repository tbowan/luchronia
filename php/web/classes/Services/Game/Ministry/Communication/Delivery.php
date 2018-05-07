<?php

namespace Services\Game\Ministry\Communication ;

class Delivery extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Delivery::getBddTable())) ;
    }
    
    public function getView() {
        return new \Widget\Game\Ministry\Communication\Delivery($this->id) ;
    }
    
}
