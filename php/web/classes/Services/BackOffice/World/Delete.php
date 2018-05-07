<?php

namespace Services\BackOffice\World ;

use Model\Game\World;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Delete extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(World::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->name)) ;
    }
    
    public function onProceed($data) {
        $this->_world->delete() ;
    }
    
}
