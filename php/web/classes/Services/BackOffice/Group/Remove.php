<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Role;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Remove extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Role::getBddTable())) ;
    }

    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM_ASSOC(
                $this->id->user->getName(),
                $this->id->group->name)) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
