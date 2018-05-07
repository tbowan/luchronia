<?php

namespace Services\Game\Building\TradingSkill ;

class Buy extends \Services\Base\Door {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("sell", new \Quantyl\Form\Model\Id(\Model\Game\Trading\Skill::getBddTable())) ;
    }
    
    public function getView() {
        $skname = $this->sell->skill->classname ;
        return new \Quantyl\Answer\Redirect("/Game/Building/TradingSkill/Buy/$skname?sell={$this->sell->id}") ;
    }

    public function getCity() {
        return $this->sell->instance->city ;
    }

}
