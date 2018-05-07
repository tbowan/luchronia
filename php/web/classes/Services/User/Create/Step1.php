<?php

namespace Services\User\Create ;

class Step1 extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CREATE_RACE_MESSAGE()) ;
        $form->addInput("race", new \Quantyl\Form\Model\Enum(\Model\Enums\Race::getBddTable(), \I18n::CHOSE_RACE()))
             ->setValue($this->race);
    }

    public function createData($data) {
        $this->race = $data["race"] ;
    }
    
    public function getNextUrl() {
        return "/User/Create/Step2" ;
    }
    
    public function getPrevUrl() {
        return "" ;
    }
    
    public function addSubmit(\Quantyl\Form\Form &$form) {
        $form->addSubmit("next", new \Quantyl\Form\Fields\Submit(\I18n::NEXT_STEP()))
             ->setCallBack($this, "onNext") ;
    }

}
