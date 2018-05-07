<?php

namespace Services\BackOffice\User ;

use Model\Identity\User;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Widget\BackOffice\User\UserDetail;

class Show extends \Services\Base\Admin {

    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(User::getBddTable())) ;
    }
    
    public function getView() {
        return new UserDetail($this->id) ;
    }
    
    public function getTitle() {
        if ($this->id === null) {
            return parent::getTitle() ;
        } else {
            return $this->id->getName() ;
        }
    }
    
}
