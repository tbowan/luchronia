<?php

namespace Services\Game\Forum ;

class Thread extends \Quantyl\Service\EnhancedService{
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("thread", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Thread::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if (! $this->thread->category->canRW($char)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_FORUM_CATEGORY_FORBIDEN()) ;
        }
    }
    
    public function getView() {
        $this->thread->viewed++ ;
        $this->thread->update() ;
        
        $me = (isset($_SESSION["char"]) ? $_SESSION["char"] : null ) ;
        
        if ($me != null) {
            $lp = \Model\Game\Forum\Post::LastFromThread($this->thread) ;
            \Model\Game\Forum\Follow::notify($this->thread, $me, $lp) ;
        }
        
        return new \Answer\View\Game\Building\Forum\Thread($this, $this->thread, $me) ;
        return new \Answer\Widget\Game\Forum\Thread($this->thread, $me) ;
    }
    
    public function getTitle() {
        if ($this->thread != null ) {
            return $this->thread->category->getName() ;
        } else {
            return parent::getTitle();
        }
    }
    
}
