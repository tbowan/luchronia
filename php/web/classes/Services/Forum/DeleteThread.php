<?php

namespace Services\Forum ;

use Model\Forum\Thread;
use Quantyl\Answer\Redirect;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class DeleteThread extends Services\Base\ForumModerate {
    
    public function fillParamFormForm(Form &$form) {
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
        $form->setMessage(\I18n::DELETE_CONFIRM($this->id->title)) ;
    }
    
    public function onProceed($data) {
        $catid = $this->id->category->id ;
        $this->id->delete() ;
        // sinon, on a une erreur "bad request" vu que le thread n'existe pas
        $this->setAnswer(new Redirect("/Forum/Category?id={$catid}")) ;
    }
    
}
