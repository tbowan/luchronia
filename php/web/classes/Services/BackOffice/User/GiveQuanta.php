<?php

namespace Services\BackOffice\User ;

class GiveQuanta extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("user", new \Quantyl\Form\Model\Id(\Model\Identity\User::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::BACKOFFICE_GIVE_QUANTA_MESSAGE($this->user->getName())) ;
        $form->addInput("amount", new \Quantyl\Form\Fields\Integer(\I18n::QUANTAS())) ;
    }
    
    public function onProceed($data) {
        $this->user->quanta += $data["amount"] ;
        $this->user->update() ;
        
    }
    
}
