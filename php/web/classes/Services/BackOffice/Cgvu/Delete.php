<?php

namespace Services\BackOffice\Cgvu ;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Identity\Cgvu::getBddTable())) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->file)) ;
    }
    
    public function onProceed($data) {
        
        $this->id->delete() ;
        
        return ;
    }
    
    
}

