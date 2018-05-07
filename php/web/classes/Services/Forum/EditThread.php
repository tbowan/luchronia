<?php

namespace Services\Forum ;

use Model\Forum\Category;
use Model\Forum\Thread;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Form\Model\Select;


class EditThread extends \Services\Base\ForumModerate {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Thread::getBddTable())) ;
    }
    
    public function getCategory() {
        if ($this->id === null) {
            return null ;
        } else {
            return $this->id->category ;
        }
    }

    public function fillDataForm(Form &$form) {
        $form->addInput("title", new Text(\I18n::TITLE()))
             ->setValue($this->id->title);
        
        $form->addInput("category", new Select(Category::getBddTable(), \I18n::CATEGORY(), true))
             ->setValue($this->id->category) ;
        
        $form->addInput("pinned", new Boolean(\I18n::PINNED()))
             ->setValue($this->id->pinned) ;
        $form->addInput("closed", new Boolean(\I18n::CLOSED()))
             ->setValue($this->id->closed) ;
    }
    
    public function onProceed($data) {
        
        $this->id->title    = $data["title"] ;
        $this->id->category = $data["category"] ;
        $this->id->pinned   = $data["pinned"] ;
        $this->id->closed   = $data["closed"] ;
        $this->id->update() ;
        
        return ;
    }
    
}
