<?php

namespace Services\Game\Forum ;

class AddMessage extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("thread", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Thread::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if ($char == null || ! $this->thread->category->canRW($char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_FORUM_CATEGORY_FORBIDEN()) ;
        }
        
        if ($this->thread->locked) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addInput("content",  new \Quantyl\Form\Fields\FilteredHtml(\I18n::POST_REPLY(),  true)) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND()))
             ->setCallBack($this, "onProceed");
    }
    
    public function onProceed($data) {
        $message = \Model\Game\Forum\Post::createFromValues(array(
            "thread"        => $this->thread,
            "pub_author"    => $this->getCharacter(),
            "pub_date"      => time(),
            "content"       => $data["content"]
        )) ;
        
        //Ici, si le thread est suivi, alors envoyer un mail.
        foreach (\Model\Game\Forum\Follow::GetFollowers($this->thread) as $follow){
            \Model\Event\Listening::Social_Game_Forum_Follow($follow->character, $message) ;
        }        
    }
    
    

    
}
