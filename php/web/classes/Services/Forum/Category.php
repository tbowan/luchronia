<?php

namespace Services\Forum ;

use Model\Forum\Category as MCategory;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;


class Category extends \Services\Base\ForumView {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(MCategory::getBddTable(), "", false)) ;
    }
    
    public function getCategory() {
        return $this->id ;
    }
    
    public function getView() {
        return new \Answer\View\Forum\Category($this, $this->id) ;
    }
    
    public function getTitle() {
        return $this->id->title ;
    }
    
}
