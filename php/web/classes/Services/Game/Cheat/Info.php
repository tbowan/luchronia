<?php

namespace Services\Game\Cheat ;

class Info extends \Quantyl\Service\EnhancedService {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable(), "", false)) ;
    }
    
    public function getView() {
        return new \Widget\Game\Map\Cheat($this->city);
    }
    
    
}