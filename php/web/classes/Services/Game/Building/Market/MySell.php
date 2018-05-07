<?php

namespace Services\Game\Building\Market ;

class MySell extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }

    public function getView() {
        return new \Answer\Widget\Game\Building\Market\MySell($this->instance, $this->getCharacter()) ;
    }
    
    public function getCity() {
        return $this->instance->city ;
    }

}
