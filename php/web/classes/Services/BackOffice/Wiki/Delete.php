<?php

namespace Services\BackOffice\Wiki ;

use Model\Wiki\Page;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Page::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->title)) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
