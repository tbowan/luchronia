<?php

namespace Services\BackOffice\Forum ;

use Model\Forum\Category;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class CategoryMoveBefore extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Category::getBddTable())) ;
    }
    
    public function getView() {
        $this->id->moveBefore() ;
    }
    
}
