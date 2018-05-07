<?php

namespace Services\User\Create ;

class Step3 extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::CREATE_AVATAR_MESSAGE()) ;
        $form->addInput("avatar", new \Form\Avatar($this->race, $this->sex, \I18n::AVATAR()))
             ->setValue($this->avatar);
        return $form ;
    }

    public function createData($data) {
        $this->avatar = $data["avatar"] ;
    }
    
    public function getNextUrl() {
        return "/User/Create/Step4" ;
    }

    public function getPrevUrl() {
        return "/User/Create/Step2" ;
    }
}
