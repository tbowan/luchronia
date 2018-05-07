<?php

namespace Services\Forum ;

use Model\Forum\Post;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class DeleteMessage extends \Services\Base\ForumModerate {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(Post::getBddTable())) ;
    }
    
    public function getCategory() {
        if ($this->id === null) {
            return null ;
        } else {
            return $this->id->thread->category ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->content)) ;
    }
    
    public function onProceed($data) {
        $this->id->delete() ;
    }
    
}
