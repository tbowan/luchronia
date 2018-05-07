<?php

namespace Services\Game\City ;

class Population extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("city", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }

    public function getView() {
        return new \Answer\Widget\Game\Social\Population($this->city);
    }
    
}
