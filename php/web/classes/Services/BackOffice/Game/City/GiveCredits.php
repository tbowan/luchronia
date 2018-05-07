<?php

namespace Services\BackOffice\Game\City ;


class GiveCredits extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\City::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("credits", new \Quantyl\Form\Fields\Float(\I18n::CREDITS(), true))
             ->setValue($this->id->credits);
    }
    
    public function onProceed($data) {
        $this->id->credits = $data["credits"] ;
        $this->id->update() ;
    }
    
}
