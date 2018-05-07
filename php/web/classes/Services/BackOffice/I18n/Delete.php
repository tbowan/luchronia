<?php

namespace Services\BackOffice\I18n ;

use Model\I18n\Translation;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("translation", new Id(Translation::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->translation->key)) ;
    }
    
    public function onProceed($data) {
        $this->translation->delete() ;
    }

}
