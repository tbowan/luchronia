<?php

namespace Services\Game\Post ;

class Mailbox extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("mailbox", new \Quantyl\Form\Model\Id(\Model\Game\Post\Mailbox::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->mailbox->character->equals($this->getCharacter())) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {
        return new \Answer\Widget\Game\Post\Mailbox($this->mailbox) ;
    }
    
    public function getTitle() {
        if ($this->mailbox != null) {
            return $this->mailbox->getName() ;
        } else {
            return parent::getTitle() ;
        }
    }
    
}
