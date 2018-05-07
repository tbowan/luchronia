<?php

namespace Services\BackOffice\Blog ;

use Model\Blog\Category;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class DeleteCategory extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("category", new Id(Category::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->category->name)) ;
    }
    
    public function onProceed($data) {
        $this->category->delete() ;
    }
}
