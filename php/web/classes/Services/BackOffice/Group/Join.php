<?php

namespace Services\BackOffice\Group ;

use Model\Identity\Group;
use Model\Identity\Role;
use Model\Identity\User;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Name;

class Join extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("group", new Id(Group::getBddTable(), "", true)) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("user", new Name(User::getBddTable(), \I18n::NICKNAME(), true)) ;
    }
    
    public function onProceed($data) {
        Role::createFromValues(array(
            "group" => $this->group,
            "user"  => $data["user"]
        )) ;
    }
    
}
