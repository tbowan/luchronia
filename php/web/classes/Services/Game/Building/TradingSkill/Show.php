<?php

namespace Services\Game\Building\TradingSkill ;

class Show extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("instance", new \Quantyl\Form\Model\Id(\Model\Game\Building\Instance::getBddTable())) ;
        $form->addInput("skill",    new \Quantyl\Form\Model\Id(\Model\Game\Skill\Skill::getBddTable())) ;
    }

    public function getCity() {
        return $this->instance->city ;
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Building\TradingSkill\ShowSkill($this->instance, $this->skill, $this->getCharacter()) ;
    }

}
