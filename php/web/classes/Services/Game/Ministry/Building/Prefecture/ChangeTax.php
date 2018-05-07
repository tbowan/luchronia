<?php

namespace Services\Game\Ministry\Building\Prefecture ;

class ChangeTax extends \Services\Base\Minister {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("prefecture", new \Quantyl\Form\Model\Id(\Model\Game\Building\Prefecture::getBddTable())) ;
    }

    public function getCity() {
        return $this->prefecture->instance->city ;
    }

    public function getMinistry() {
        return $this->prefecture->instance->job->ministry ;
    }

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("prestige_in",  new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_IN()))
                 ->setValue($this->prefecture->prestige_in);
        $form->addInput("prestige_out", new \Quantyl\Form\Fields\Percentage(\I18n::PRESTIGE_OUT()))
                 ->setValue($this->prefecture->prestige_out);
    }
    
    public function onProceed($data) {
        
        $this->prefecture->prestige_in  = $data["prestige_in"] ;
        $this->prefecture->prestige_out = $data["prestige_out"] ;
        $this->prefecture->update() ;
        
    }
    
}
