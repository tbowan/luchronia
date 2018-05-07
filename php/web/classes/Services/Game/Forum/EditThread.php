<?php


namespace Services\Game\Forum ;

class EditThread extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("thread", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Thread::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if ($char == null || !\Model\Game\Forum\Moderator::isModerator($char, $this->thread->category)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        
        $form->addInput("title", new \Quantyl\Form\Fields\Text(\I18n::TITLE()))
             ->setValue($this->thread->title) ;
        $form->addInput("pinned", new \Quantyl\Form\Fields\Boolean(\I18n::PINNED()))
             ->setValue($this->thread->pinned);
        $form->addInput("closed", new \Quantyl\Form\Fields\Boolean(\I18n::CLOSED()))
             ->setValue($this->thread->closed);
        $form->addInput("category", new \Form\GameForumCategory($this->thread->category->instance, \I18n::CATEGORY()))
             ->setValue($this->thread->category);
        
    }
    
    public function onProceed($data) {
        
        $this->thread->title  = $data["title"] ;
        $this->thread->pinned = $data["pinned"] ;
        $this->thread->closed = $data["closed"] ;
        $this->thread->category = $data["category"] ;
        $this->thread->update() ;
    }
    
}
