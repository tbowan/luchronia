<?php

namespace Services\User\Create ;

class Step2 extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CREATE_GENDER_MESSAGE()) ;
        $form->addInput("sex", new \Quantyl\Form\Model\Enum(\Model\Enums\Sex::getBddTable(), \I18n::CHOSE_GENDER(), true))
             ->setValue($this->sex);
        return $form ;
    }

    public function createData($data) {
        $this->sex = $data["sex"] ;
    }
    
    public function getNextUrl() {
        return "/User/Create/Step3" ;
    }

    public function getPrevUrl() {
        return "/User/Create/Step1" ;
    }

}
