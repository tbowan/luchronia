<?php

namespace Services\Game\Ministry\Government ;

class Log extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function getView() {
        return new \Widget\Game\Ministry\Government\Log($this->city) ;
    }
    
}
