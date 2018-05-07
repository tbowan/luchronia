<?php

namespace Services\Game\Building\TradingSkill ;

class Mine extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
    }

    public function getCity() {
        return $this->instance->city ;
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Building\TradingSkill\ShowMine($this->instance, $this->getCharacter()) ;
    }

}
