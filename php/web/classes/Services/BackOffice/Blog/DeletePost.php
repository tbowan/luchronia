<?php

namespace Services\BackOffice\Blog ;

use Model\Blog\Post;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class DeletePost extends \Services\Base\Admin {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("post", new Id(Post::getBddTable())) ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->setMessage(\I18n::DELETE_CONFIRM($this->post->title)) ;
    }
    
    public function onProceed($data) {
        $this->post->delete() ;
    }

}
