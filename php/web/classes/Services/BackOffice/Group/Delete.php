<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Group::getBddTable(), "", true)) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->name)) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
