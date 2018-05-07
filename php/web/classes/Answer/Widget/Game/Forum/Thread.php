<?php

namespace Answer\Widget\Game\Forum ;

class Thread extends \Quantyl\Answer\Widget {
    
    private $_thread ;
    private $_me ;
    private $_moderator ;
    
    public function __construct(\Model\Game\Forum\Thread $thread, $me) {
        parent::__construct();
        $this->_thread = $thread ;
        $this->_me = $me ;
        $this->_moderator = $me != null && \Model\Game\Forum\Moderator::isModerator($me, $thread->category) ;
    }
    
    public function addPost(\Model\Game\Forum\Post $p, $i) {
        
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $p->pub_author->getImage("mini") ;
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
        
        $res .= "<div class=\"post-right\">" ;
        if ($this->_moderator) {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/EditMessage?id={$p->id}", \I18n::EDIT()) ;
        }
        $res .= "</div>" ;
        
        $res .= "</div>" ;
        return $res ;

    }
    
    public function addForm() {
        $form = new \Quantyl\Form\Form() ;
        $form->addInput("_referer", new \Quantyl\Form\Fields\Caller("/Game/Forum/Thread?thread={$this->_thread->id}")) ;
        $form->addInput("content",  new \Quantyl\Form\Fields\FilteredHtml(\I18n::POST_REPLY(),  true)) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SEND())) ;
        return $form->getContent("/Game/Forum/AddMessage?thread={$this->_thread->id}") ;
    }
    
    public function getModerator() {
        $res = "" ;
        if ($this->_moderator) {
            $res .= "<p><strong>" . \I18n::MODERATE_THREAD() . " :</strong>" ;
            $res .= " " . new \Quantyl\XML\Html\A("/Game/Forum/EditThread?thread={$this->_thread->id}", \I18n::EDIT()) ;
            $res .= " " . new \Quantyl\XML\Html\A("/Game/Forum/DeleteThread?thread={$this->_thread->id}", \I18n::DELETE()) ;
            $res .= "</p>";
        }
        return $res ;
    }
    
    public function getFollowers() {
        $following = ($this->_me != null ? \Model\Game\Forum\Follow::getFollowing($this->_thread, $this->_me) : null) ;
        
        $res = "<p><strong>" . \I18n::FOLLOWERS() . " :</strong> " ;
        $res .= \Model\Game\Forum\Follow::CountFromThread($this->_thread) ;
        $res .= " (" ;
        if ($following != null) {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/Unfollow?follow={$following->id}", \I18n::CANCEL_FOLLOWING()) ;
        } else {
            $res .= new \Quantyl\XML\Html\A("/Game/Forum/Follow?thread={$this->_thread->id}", \I18n::FOLLOW()) ;
        }    
        $res .= ")</p>";
        return $res ;
    }
    
    public function getContent() {
        $res = "<h2>" . $this->_thread->title . "</h2>" ;
        
        $res .= $this->getModerator() ;
        $res .= $this->getFollowers() ;
        
        $i = 1 ;
        foreach (\Model\Game\Forum\Post::getFromThread($this->_thread) as $post) {
            $res .= $this->addPost($post, $i) ;
            $i++ ;
        }
        
        if (! $this->_thread->closed) {
            $res .= $this->addForm() ;
        }
        
        return $res ;
    }
    
}
