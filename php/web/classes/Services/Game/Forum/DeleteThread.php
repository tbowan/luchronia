<?php

namespace Services\Game\Forum ;

class DeleteThread extends \Services\Base\Character {
    
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
        $form->addMessage(\I18n::DELETE_THREAD_MESSAGE($this->thread->getName())) ;
    }
    
    public function onProceed($data) {
        $this->thread->delete() ;
    }
    
    

    
}
