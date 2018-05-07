<?php

namespace Services\Game\Building\Market ;

class ShowSell extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
        $form->addInput("ressource", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Item::getBddTable())) ;
    }

    public function getView() {
        $can_preempt = \Model\Game\Politic\Minister::hasPower(
                $this->getCharacter(),
                $this->getCity(),
                \Model\Game\Politic\Ministry::GetByName("Commerce")
                ) ;
        
        return new \Answer\Widget\Game\Building\Market\SellDetail($this->instance, $this->ressource, $can_preempt) ;
    }
    
    public function getCity() {
        return $this->instance->city ;
    }

}
