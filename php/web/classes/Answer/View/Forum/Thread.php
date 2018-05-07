<?php

namespace Answer\View\Forum ;

class Thread extends \Answer\View\Base {
    
    private $_thread ;
    private $_novote ;
    private $_user ;
    private $_char ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Forum\Thread $thread, $user, $novote, \Model\Game\Character $char) {
        parent::__construct($service, "");
        $this->_thread = $thread ;
        $this->_novote = $novote ;
        $this->_user   = $user ;
        $this->_char   = $char ;
    }
    
    public function canModerate() {
        return $this->_thread->category->canModerate($this->_user) ;
    }
    
    public function addPost(\Model\Forum\Post $p, $i, $mod) {
        
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $p->author->getImage("mini") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-right\">" ;
        if ($mod) {
            $res .= new \Quantyl\XML\Html\A("/Forum/EditMessage?id={$p->id}", \I18n::EDIT()) ;
            $res .= new \Quantyl\XML\Html\A("/Forum/DeleteMessage?id={$p->id}", \I18n::DELETE()) ;
        }
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$p->author->id}", $p->author->getName()) ;
            $res .= " - " ;
            $res .= \I18n::_date_time($p->date - DT)  ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $p->content ;
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"clear\"></div>" ;
        
        $res .= "</div>" ;
        return $res ;

    }
    
    public function addForm() {
        $form = new \Quantyl\Form\Form() ;
        $form->addInput("_referer", new \Quantyl\Form\Fields\Caller("/Forum/Thread?id={$this->_thread->id}")) ;
        $form->addInput("content",  new \Quantyl\Form\Fields\FilteredHtml(\I18n::POST_REPLY(),  true)) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        return $form->getContent("/Forum/AddMessage?thread={$this->_thread->id}") ;
    }
   
    public function getFollowers() {
        $me = $this->_char;
        
        $following = ($me != null ? \Model\Forum\Follow::getFollowing($this->_thread, $me) : null) ;
        
        $res = "<strong>" . \I18n::FOLLOWERS() . " :</strong> " ;
        $res .= \Model\Forum\Follow::CountFromThread($this->_thread) ;
        $res .= ", " ;
        if ($following != null) {
            $res .= new \Quantyl\XML\Html\A("/Forum/Unfollow?follow={$following->id}", \I18n::CANCEL_FOLLOWING()) ;
        } else {
            $res .= new \Quantyl\XML\Html\A("/Forum/Follow?thread={$this->_thread->id}", \I18n::FOLLOW()) ;
        }    
        return $res ;
    }
    
    private function getModerate(){
        $res = "" ;
        if ($this->canModerate()) {
            $res .= "<strong>" . \I18n::MODERATE_THREAD() . "</strong> : " ;
            $res .= new \Quantyl\XML\Html\A("/Forum/EditThread?id={$this->_thread->id}", \I18n::EDIT()) ;
            $res .= new \Quantyl\XML\Html\A("/Forum/DeleteThread?id={$this->_thread->id}", \I18n::DELETE()) ;
            $res .= "";
        } 
        
        
        return $res;
    }


    public function getTplContent() {
        $res = "" ;
        
        // 1. Choices
        if (\Model\Forum\Choice::CountFromThread($this->_thread) > 0) {
            $res .= new \Answer\Widget\Misc\Section(
                    \I18n::THREAD_VOTES(),
                    "",
                    "",
                    new \Answer\Widget\Forum\Survey($this->_thread, $this->_novote)
                    ) ;
        }
        
        // 2. Messages
        $messages = "" ;
        $i = 0 ;
        $canmoderate = $this->canModerate() ;
        foreach ($this->_thread->getPost() as $post) {
            $messages .= $this->addPost($post, $i, $canmoderate) ;
            $i++ ;
        }
        
        $res .= new \Answer\Widget\Misc\Section(
                $this->_thread->title,
                $this->getFollowers(),
                $this->getModerate($canmoderate),
                $messages) ;
        
        // 3. Form to answer
        $res .= new \Answer\Widget\Misc\Section(
                \I18n::FORUM_ANSWER(),
                "", "",
                $this->addForm()) ;
        
       
        
        return $res ;
    }
    
    public function getTplTitle() {
        return $this->_thread->category->getName() ;
    }
    
    public function getTplSubTitle() {

        
        return "" ;
    }
    
    public function getTplHeaderImage() {
        if ($this->_thread->category->image != "") {
            return $this->_thread->category->getImage() ;
        } else {
            return parent::getTplHeaderImage() ;
        }
    }
    
}
