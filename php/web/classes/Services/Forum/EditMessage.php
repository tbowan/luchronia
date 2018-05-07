<?php

namespace Services\Forum ;

use Model\Forum\Post;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class EditMessage extends \Services\Base\ForumModerate {
    
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
        $form->addInput("content",  new FilteredHtml(\I18n::COMMENT(),  true))
             ->setValue($this->id->content);
    }
    
    public function onProceed($data) {
        $this->id->content = $data["content"] ;
        $this->id->update() ;
    }
    
}
