<?php

namespace Services\Forum ;

use Model\Forum\Post;
use Model\Forum\Thread;
use Quantyl\Form\Fields\FilteredHtml;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Request\Request;

class AddMessage extends \Services\Base\ForumView {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("thread", new Id(Thread::getBddTable())) ;
    }

    public function getCategory() {
        if ($this->thread != null) {
            return $this->thread->category ;
        } else {
            return null ;
        }
    }
    
    public function checkPermission(Request $req) {
        parent::checkPermission($req);
        
        if ($this->thread->closed) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(Form &$form) {
        $form->addInput("content",  new FilteredHtml(\I18n::COMMENT(),  true)) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
             ->setCallBack($this, "onProceed");
    }
    
    public function onProceed($data) {
        
        $char = $this->getCharacter() ;
        
        $message = new Post() ;
        $message->thread  = $this->thread ;
        $message->date    = time() ;
        $message->content = $data["content"] ;
        $message->author  = $char ;
        $message->create() ;
        
        //Ici, si le thread est suivi, alors envoyer un mail.
        foreach (\Model\Forum\Follow::GetFollowers($this->thread) as $follow){
            \Model\Event\Listening::Social_Forum_Follow($follow->character, $message) ;
        }
        
        return ;
    }

}
