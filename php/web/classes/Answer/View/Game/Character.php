<?php

namespace Answer\View\Game ;

class Character extends \Answer\View\Base {
    
    private $_character ;
    private $_viewer ;
    private $_myself ;
    
    public function __construct($service, $character, $viewer) {
        parent::__construct($service)  ;
        $this->_character = $character ;
        $this->_viewer    = $viewer    ;
        $this->_myself    = $this->_character->equals($this->_viewer) ;
    }
    
    public function getTplContent() {
        return ""
                . new \Answer\Widget\Game\Character\IdentityCard($this->_character, $this->_viewer, $this->_myself, "card-1-2")
                . new \Answer\Widget\Game\Character\Gauge($this->_character, "card-1-2")
                
                . "<div class=\"card-1-2\">"
                . new \Answer\Widget\Game\Character\Background($this->_character, $this->_myself)
                . $this->getWall()
                . "</div>"
                
                . "<div class=\"card-1-2\">"
                . new \Answer\Widget\Game\Character\CharacteristicPrimary($this->_character)
                . new \Answer\Widget\Game\Character\CharacteristicSecondary($this->_character)
                . new \Answer\Widget\Game\Character\Skill($this->_character, $this->_myself)
                . "</div>"
                ;
    }
    
    public function getNewPostForm() {
        $form = new \Quantyl\Form\Form() ;
        $form->addInput("content", new \Quantyl\Form\Fields\FilteredHtml(\I18n::CONTENT())) ;
        $form->addInput("access",  new \Quantyl\Form\Model\Select(\Model\Enums\Access::getBddTable(), \I18n::ACCESS())) ;
        $form->addSubmit("proceed", new \Quantyl\Form\Fields\Submit(\I18n::SUBMIT_PROCEED())) ;
        return $form->getContent("/Game/Character/Post/Add") ;
    }
    
    public function addComment(\Model\Game\Social\Comment $comment) {
        $res = "<div class=\"forum-post\">" ;
        
        $res .= "<div class=\"post-left\">" ;
        $res .= $comment->author->getImage("mini") ;
        $res .= "</div>" ;           
        
        $res .= "<div class=\"post-right\">" ;
        if ($this->_myself) {
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Comment/Delete?id={$comment->id}", \I18n::DELETE()) ;
        }
        $res .= "</div>" ;
        
        $res .= "<div class=\"post-center\">" ;
            $res .= "<div class=\"post-head\">" ;
            $res .= new \Quantyl\XML\Html\A("/Game/Character/Show?id={$comment->author->id}", $comment->author->getName()) ;
            $res .= " - " ;
            $res .= \I18n::_date_time($comment->date - DT)  ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"post-content\">" ;
            $res .= $comment->content ;
            $res .= "</div>" ;
        $res .= "</div>" ;
        
        
        $res .= "</div>" ;
        return $res ;
    }
    
    public function addPost(\Model\Game\Social\Post $post) {
        
        $cmd = "" ;
        if ($this->_myself) {
            $cmd .= new \Quantyl\XML\Html\A("/Game/Character/Post/Edit?id={$post->id}", \I18n::EDIT()) ;
            $cmd .= new \Quantyl\XML\Html\A("/Game/Character/Post/Delete?id={$post->id}", \I18n::DELETE()) ;
        }
        
        $msg  = "<h2>" . \I18n::_date_time($post->date - DT) . " ($cmd)</h2>" ;
        $msg .= $post->content ;
        
        foreach (\Model\Game\Social\Comment::GetFromPost($post) as $comment) {
            $msg .= $this->addComment($comment) ;
        }
        
        $msg .= new \Quantyl\XML\Html\A("/Game/Character/Comment/Add?post={$post->id}", \I18n::BLOG_ADDCOMMENT()) ;
        
        return $msg ;
                
    }
    
    public function getWall($classes = "") {
        $msg = "" ;
        foreach (\Model\Game\Social\Post::GetFromAuthor($this->_character) as $post) {
            if ($post->access->hasCharacterAccess($this->_character, $this->_viewer)) {
                $msg .= $this->addPost($post) ;
            }
        }
        
        if ($this->_myself && $msg == "") {
            $msg .= $this->getNewPostForm() ;
        }
        
        return new \Answer\Widget\Misc\Section(
                \I18n::DIARY(),
                ($this->_myself ? new \Quantyl\XML\Html\A("/Game/Character/Post/Add", \I18n::ADD_POST()) : ""),
                "",
                $msg,
                $classes) ;

    }
    
    
}
