<?php

namespace Services\BackOffice\Treasure ;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Ressource\Treasure::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::DELETE_CONFIRM($this->id->item->getName())) ;
    }
    
    public function onProceed($data) {
        
        $this->id->delete() ;
    }
    
}
