<?php


namespace Services\Game\Forum ;

class EditMessage extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Forum\Post::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        $char = (isset($_SESSION["char"]) ? $_SESSION["char"] : null) ;
        if ($char == null || ! \Model\Game\Forum\Moderator::isModerator($char, $this->id->thread->category)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
        
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::FORUM_MODERATE_MESSAGE()) ;
        $form->addInput("content", new \Quantyl\Form\Fields\FilteredHtml(\I18n::MESSAGE()))
             ->setValue($this->id->content) ;
    }
    
    public function onProceed($data) {
        
        $this->id->content      = $data["content"] ;
        $this->id->mod_author   = $this->getCharacter() ;
        $this->id->mod_date     = time() ;
        $this->id->update() ;
    }
    
}
