<?php

namespace Answer\View\Game\Building\Forum ;

class Thread extends \Answer\View\Base {
    
    private $_thread ;
    private $_char ;
    
    public function __construct(\Quantyl\Service\EnhancedService $service, \Model\Game\Forum\Thread $thread, \Model\Game\Character $char) {
        parent::__construct($service);
        $this->_thread    = $thread ;
        $this->_char      = $char ;
        $this->_moderator = \Model\Game\Forum\Moderator::isModerator($char, $thread->category) ;
    }
    
    public function addPost(\Model\Game\Forum\Post $p, $i) {
        
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $p->pub_author->getImage("mini") ;
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-right\">" ;
        if ($this->_moderator) {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/EditMessage?id={$p->id}", \I18n::EDIT()) ;
        }
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$p->pub_author->id}", $p->pub_author->getName()) ;
            $res .= " - " ;
            $res .= \I18n::_date_time($p->pub_date - DT)  ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $p->content ;
            if ($p->mod_author != null) {
                $res .= "<hr/>"  ;
                $res .= \I18n::MODERATE_BY(
                        $p->mod_author->id,
                        $p->mod_author->getName(),
                        \I18n::_date_time($p->mod_date - DT)
                        ) ;
            }
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        
        
        $res .= "</div>" ;
        return $res ;

    }
    
    public function addForm() {
        $form = new \Quantyl\Form\Form() ;
        $form->addInput("_referer", new \Quantyl\Form\Fields\Caller("/Game/Forum/Thread?thread={$this->_thread->id}")) ;
        $form->addInput("content",  new \Quantyl\Form\Fields\FilteredHtml(\I18n::POST_REPLY(),  true)) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        $html = $form->getContent("/Game/Forum/AddMessage?thread={$this->_thread->id}") ;
        
        return new \Answer\Widget\Misc\Section(\I18n::ANSWER(), "", "", $html, "") ;
    }
    
    public function getModerator() {
        $res = "" ;
        if ($this->_moderator) {
            $res .= " " . new \Quantyl\XML\Html\A("/Game/Forum/EditThread?thread={$this->_thread->id}", \I18n::EDIT()) ;
            $res .= " " . new \Quantyl\XML\Html\A("/Game/Forum/DeleteThread?thread={$this->_thread->id}", \I18n::DELETE()) ;
        }
        return $res ;
    }
    
    public function getFollowers() {
        $following = ($this->_char != null ? \Model\Game\Forum\Follow::getFollowing($this->_thread, $this->_char) : null) ;
        
        $res = "<strong>" . \I18n::FOLLOWERS() . " :</strong> " ;
        $res .= \Model\Game\Forum\Follow::CountFromThread($this->_thread) ;
        $res .= " (" ;
        if ($following != null) {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/Unfollow?follow={$following->id}", \I18n::CANCEL_FOLLOWING()) ;
        } else {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/Follow?thread={$this->_thread->id}", \I18n::FOLLOW()) ;
        }    
        $res .= ")";
        return $res ;
    }
    
    public function getTplContent() {
        $mod = $this->getModerator() ;
        $fol = $this->getFollowers() ;
        
        $i = 1 ;
        $msg = "" ;
        foreach (\Model\Game\Forum\Post::getFromThread($this->_thread) as $post) {
            $msg .= $this->addPost($post, $i) ;
            $i++ ;
        }
        
        $res = "" ;
        $res .= new \Answer\Widget\Misc\Section($this->_thread->title, $mod, $fol, $msg, "") ;
        
        if (! $this->_thread->closed) {
            $res .= $this->addForm() ;
        }
        
        
        return $res ;
    }
    
}
