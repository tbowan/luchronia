<?php

namespace Services\Game\Post ;

class Mail extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("inbox", new \Quantyl\Form\Model\Id(\Model\Game\Post\Inbox::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->inbox->box->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {
        $this->inbox->read = true ;
        $this->inbox->update() ;
        return new \Answer\Widget\Game\Post\Mail($this->inbox, $this->getCharacter()) ;
    }
    
    public function getTitle() {
        if ($this->inbox != null) {
            return $this->inbox->mail->title ;
        } else {
            return parent::getTitle() ;
        }
    }
    
}
