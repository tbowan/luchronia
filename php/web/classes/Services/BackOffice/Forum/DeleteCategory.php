<?php

namespace Services\BackOffice\Forum ;

use Model\Forum\Category;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class DeleteCategory extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Category::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->title)) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
