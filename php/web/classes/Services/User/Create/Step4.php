<?php

namespace Services\User\Create ;

class Step4 extends CreateStep {
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
            
        if ($this->name != "") {
            $name = $this->name ;
        } else {
            $name = \Misc\NameGenerator\Character::generate($this->race, $this->sex) ;
        }
        
        $form->setMessage(\I18n::CREATE_NAME_MESSAGE()) ;
        $form->addInput("name", new \Quantyl\Form\Fields\Text(\I18n::CHOSE_NAME()))
             ->setValue($name) ;
        return $form ;
    }

    public function createData($data) {
        $this->name = $data["name"] ;
    }
    
    public function getNextUrl() {
        return "/User/Create/Step5" ;
    }

    public function getPrevUrl() {
        return "/User/Create/Step3" ;
    }
}
