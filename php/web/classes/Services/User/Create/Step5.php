<?php

namespace Services\User\Create ;

class Step5 extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CREATE_STARTPOSITION_MESSAGE()) ;
        $form->addInput("position", new \Form\StartPosition())
             ->setValue($this->position) ;
        return $form ;
    }

    public function createData($data) {
        $this->position = $data["position"] ;
    }
    
    public function getNextUrl() {
        return "/User/Create/StepF" ;
    }

    public function getPrevUrl() {
        return "/User/Create/Step4" ;
    }
}
