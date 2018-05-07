<?php

namespace Services\Forum ;

use Model\Forum\Category as MCategory;
use Model\Forum\Post;
use Model\Forum\Thread;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Fields\Submit;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;
use Quantyl\Service\EnhancedService;

class AddThread extends \Services\Base\ForumView {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("category", new Id(MCategory::getBddTable(), true)) ;
    }

    public function getCategory() {
        return $this->category ;
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("title",    new Text        (\I18n::TITLE()         )) ;
        $form->addInput("content",  new FilteredHtml(\I18n::COMMENT(),  true)) ;
    }
    
    public function onProceed($data) {
        
        $thread = new Thread() ;
        $thread->title = $data["title"] ;
        $thread->category = $this->category ;
        $thread->pinned = false ;
        $thread->closed = false ;
        $thread->viewed = 0 ;
        $thread->author = $_SESSION["char"] ;
        $thread->create() ;
        
        $message = new Post() ;
        $message->thread = $thread ;
        $message->date = time() ;
        $message->content = $data["content"] ;
        $message->author = $_SESSION["char"] ;
        $message->create() ;
        
        return  ;
    }
    
}
